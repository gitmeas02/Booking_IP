import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';

export function useCalendarManager() {
  // Constants
  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  // Reactive state
  const selectedMonth = ref(new Date().getMonth());
  const selectedYear = ref(new Date().getFullYear());
  const rooms = ref([]);
  const loading = ref(false);
  const error = ref('');

  // Single date popup state
  const showPopup = ref(false);
  const selectedRoom = ref({});
  const selectedStartDate = ref('');
  const selectedEndDate = ref('');
  const selectedPrice = ref(0);
  const selectedStatus = ref('');
  const showUnblockModal = ref(false);
  const selectedBlockToRemove = ref({});

  // Multi-date selection state
  const selectionMode = ref(false);
  const selectedDates = ref([]);
  const hoverDate = ref('');
  const selectionStartDate = ref('');
  const selectionEndDate = ref('');
  const showMultiBlockModal = ref(false);
  const shiftPressed = ref(false);

  // Computed properties
  const monthDays = computed(() => {
    const days = [];
    const date = new Date(selectedYear.value, selectedMonth.value, 1);
    
    while (date.getMonth() === selectedMonth.value) {
      days.push(formatDate(new Date(date)));
      date.setDate(date.getDate() + 1);
    }
    
    return days;
  });

  const firstDay = computed(() => {
    return parseDate(monthDays.value[0]);
  });

  const lastDay = computed(() => {
    return parseDate(monthDays.value[monthDays.value.length - 1]);
  });

  const availableDaysCount = computed(() => {
    return rooms.value.reduce((total, room) => {
      return total + monthDays.value.filter(day => {
        const date = parseDate(day);
        return !isDateBooked(room, date) && !isDateBlocked(room, date);
      }).length;
    }, 0);
  });

  const bookedDaysCount = computed(() => {
    return rooms.value.reduce((total, room) => {
      return total + monthDays.value.filter(day => {
        const date = parseDate(day);
        return isDateBooked(room, date);
      }).length;
    }, 0);
  });

  // Utility functions
  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function parseDate(str) {
    const [y, m, d] = str.split('-').map(Number);
    return new Date(y, m - 1, d);
  }

  function formatDisplayDate(dateStr) {
    const date = parseDate(dateStr);
    return date.getDate();
  }

  function formatWeekday(dateStr) {
    const date = parseDate(dateStr);
    return date.toLocaleDateString('en-US', { weekday: 'short' });
  }

  function daysBetween(start, end) {
    const diff = new Date(end) - new Date(start);
    return Math.floor(diff / (1000 * 60 * 60 * 24));
  }

  function getNextDay(dateStr) {
    const date = parseDate(dateStr);
    date.setDate(date.getDate() + 1);
    return formatDate(date);
  }

  function handleImageError(event) {
    // Placeholder for image error handling
  }

  // Navigation functions
  function previousMonth() {
    if (selectedMonth.value === 0) {
      selectedMonth.value = 11;
      selectedYear.value--;
    } else {
      selectedMonth.value--;
    }
  }

  function nextMonth() {
    if (selectedMonth.value === 11) {
      selectedMonth.value = 0;
      selectedYear.value++;
    } else {
      selectedMonth.value++;
    }
  }

  function goToToday() {
    const today = new Date();
    selectedMonth.value = today.getMonth();
    selectedYear.value = today.getFullYear();
  }

  // Room data management
  async function fetchRooms() {
    loading.value = true;
    error.value = '';

    try {
      const response = await axios.get('http://localhost:5000/hotels', {
        timeout: 10000
      });

      rooms.value = response.data.flatMap(hotel =>
        hotel.rooms?.map(room => ({
          ...room,
          hotel: hotel.name,
          hotelId: hotel.id,
          bookings: room.bookings || [],
          blockedDates: room.blockedDates || [],
          price: room.price || [],
          basePrice: room.basePrice || 0
        })) || []
      );
    } catch (err) {
      console.error('Error fetching rooms:', err);
      error.value = err.response
        ? `Failed to fetch rooms: ${err.response.statusText}`
        : 'Failed to fetch rooms. Please check your network or try again later.';
    } finally {
      loading.value = false;
    }
  }

  // Status checking functions
  function isDateBooked(room, date) {
    if (!room.bookings) return false;
    
    return room.bookings.some(booking => {
      const bookingStart = parseDate(booking.startDate);
      const bookingEnd = parseDate(booking.endDate);
      return date >= bookingStart && date < bookingEnd;
    });
  }

  function isDateBlocked(room, date) {
    if (!room.blockedDates) return false;
    
    return room.blockedDates.some(blocked => {
      const blockedStart = parseDate(blocked.startDate);
      const blockedEnd = parseDate(blocked.endDate);
      return date >= blockedStart && date < blockedEnd;
    });
  }

  function getDayPrice(room, dateStr) {
    const date = parseDate(dateStr);
    
    if (room.price && room.price.length > 0) {
      for (const priceRange of room.price) {
        const startDate = parseDate(priceRange.startDate);
        const endDate = parseDate(priceRange.endDate);
        
        if (date >= startDate && date < endDate) {
          return priceRange.amount;
        }
      }
    }
    
    return room.basePrice || 0;
  }

  function getDayStatus(room, dateStr) {
    const date = parseDate(dateStr);
    
    if (isDateBooked(room, date)) return 'Booked';
    if (isDateBlocked(room, date)) return 'Blocked';
    return 'Available';
  }

  function getDayStatusClassWithUnblock(room, dayStr) {
    const date = parseDate(dayStr);
    let classes = 'relative ';
    
    if (selectionMode.value && selectedRoom.value.id === room.id) {
      if (room._unblockMode) {
        if (isDateBooked(room, date)) {
          classes += 'bg-red-100 text-red-700 border-red-200 cursor-not-allowed opacity-50';
        } else if (isDateBlocked(room, date)) {
          classes += 'bg-gray-200 text-gray-600 cursor-pointer hover:bg-orange-100 hover:border-orange-300';
        } else {
          classes += 'bg-green-50 text-green-700 cursor-not-allowed opacity-50';
        }
      } else {
        if (isDateBooked(room, date)) {
          classes += 'bg-red-100 text-red-700 border-red-200 cursor-not-allowed opacity-50';
        } else if (isDateBlocked(room, date)) {
          classes += 'bg-gray-200 text-gray-600 cursor-pointer hover:bg-purple-100';
        } else {
          classes += 'bg-green-50 text-green-700 cursor-pointer hover:bg-purple-100 hover:border-purple-300';
        }
      }
    } else {
      if (isDateBooked(room, date)) {
        classes += 'bg-red-100 text-red-700 border-red-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 booked-pulse';
      } else if (isDateBlocked(room, date)) {
        classes += 'bg-gray-200 text-gray-600 cursor-pointer hover:bg-blue-50 hover:border-blue-300';
      } else {
        classes += 'bg-green-50 text-green-700 cursor-pointer hover:bg-blue-50 hover:border-blue-300';
      }
    }
    
    return classes;
  }

  // Multi-date selection functions
  function startMultiSelection(room) {
    selectionMode.value = true;
    selectedRoom.value = room;
    selectedDates.value = [];
    selectionStartDate.value = '';
    selectionEndDate.value = '';
    hoverDate.value = '';
  }

  function startMultiUnblockSelection(room) {
    selectionMode.value = true;
    selectedRoom.value = room;
    selectedDates.value = [];
    selectionStartDate.value = '';
    selectionEndDate.value = '';
    hoverDate.value = '';
    room._unblockMode = true;
  }

  function cancelSelectionWithUnblock() {
    const room = rooms.value.find(r => r.id === selectedRoom.value.id);
    if (room) {
      room._unblockMode = false;
    }
    
    selectionMode.value = false;
    selectedRoom.value = {};
    selectedDates.value = [];
    selectionStartDate.value = '';
    selectionEndDate.value = '';
    hoverDate.value = '';
  }

  function isDateSelected(room, dateStr) {
    return selectionMode.value && 
           selectedRoom.value.id === room.id && 
           selectedDates.value.includes(dateStr);
  }

  function isDateInHoverRange(room, dateStr) {
    if (!selectionMode.value || selectedRoom.value.id !== room.id || !hoverDate.value) {
      return false;
    }
    
    if (selectedDates.value.length === 0) {
      return false;
    }
    
    const firstSelected = selectedDates.value[0];
    const start = firstSelected < hoverDate.value ? firstSelected : hoverDate.value;
    const end = firstSelected < hoverDate.value ? hoverDate.value : firstSelected;
    
    return dateStr >= start && dateStr <= end && !selectedDates.value.includes(dateStr);
  }

  function toggleDateSelection(room, dateStr) {
    if (selectedRoom.value.id !== room.id) return;
    
    const date = parseDate(dateStr);
    
    if (isDateBooked(room, date)) {
      return;
    }
    
    const index = selectedDates.value.indexOf(dateStr);
    if (index > -1) {
      selectedDates.value.splice(index, 1);
    } else {
      selectedDates.value.push(dateStr);
    }
    
    if (selectedDates.value.length > 0) {
      const sortedDates = [...selectedDates.value].sort();
      selectionStartDate.value = sortedDates[0];
      selectionEndDate.value = sortedDates[sortedDates.length - 1];
    } else {
      selectionStartDate.value = '';
      selectionEndDate.value = '';
    }
  }

  function toggleDateSelectionUnblock(room, dateStr) {
    if (selectedRoom.value.id !== room.id) return;
    
    const date = parseDate(dateStr);
    
    if (room._unblockMode && !isDateBlocked(room, date)) {
      return;
    }
    
    if (isDateBooked(room, date)) {
      return;
    }
    
    const index = selectedDates.value.indexOf(dateStr);
    if (index > -1) {
      selectedDates.value.splice(index, 1);
    } else {
      selectedDates.value.push(dateStr);
    }
    
    if (selectedDates.value.length > 0) {
      const sortedDates = [...selectedDates.value].sort();
      selectionStartDate.value = sortedDates[0];
      selectionEndDate.value = sortedDates[sortedDates.length - 1];
    } else {
      selectionStartDate.value = '';
      selectionEndDate.value = '';
    }
  }

  function selectDateRange(room, startDate, endDate) {
    if (selectedRoom.value.id !== room.id) return;
    
    const start = parseDate(startDate);
    const end = parseDate(endDate);
    const actualStart = start <= end ? start : end;
    const actualEnd = start <= end ? end : start;
    
    const currentDate = new Date(actualStart);
    while (currentDate <= actualEnd) {
      const dateStr = formatDate(currentDate);
      if (!isDateBooked(room, currentDate) && !selectedDates.value.includes(dateStr)) {
        selectedDates.value.push(dateStr);
      }
      currentDate.setDate(currentDate.getDate() + 1);
    }
    
    if (selectedDates.value.length > 0) {
      const sortedDates = [...selectedDates.value].sort();
      selectionStartDate.value = sortedDates[0];
      selectionEndDate.value = sortedDates[sortedDates.length - 1];
    }
  }

  function confirmSelectionWithUnblock() {
    if (selectedDates.value.length === 0) return;
    showMultiBlockModal.value = true;
  }

  async function confirmMultiBlock() {
    try {
      const room = rooms.value.find(r => r.id === selectedRoom.value.id);
      if (!room) return;
      
      const sortedDates = [...selectedDates.value].sort();
      const ranges = [];
      let currentStart = sortedDates[0];
      let currentEnd = sortedDates[0];
      
      for (let i = 1; i < sortedDates.length; i++) {
        const currentDate = parseDate(sortedDates[i]);
        const prevDate = parseDate(sortedDates[i - 1]);
        const dayDiff = (currentDate - prevDate) / (1000 * 60 * 60 * 24);
        
        if (dayDiff === 1) {
          currentEnd = sortedDates[i];
        } else {
          ranges.push({ startDate: currentStart, endDate: getNextDay(currentEnd) });
          currentStart = sortedDates[i];
          currentEnd = sortedDates[i];
        }
      }
      
      ranges.push({ startDate: currentStart, endDate: getNextDay(currentEnd) });
      
      for (const range of ranges) {
        const isConflict = room.bookings.some(booking => {
          const bookingStart = parseDate(booking.startDate);
          const bookingEnd = parseDate(booking.endDate);
          const rangeStart = parseDate(range.startDate);
          const rangeEnd = parseDate(range.endDate);
          return rangeStart < bookingEnd && rangeEnd > bookingStart;
        });
        
        if (!isConflict) {
          room.blockedDates.push(range);
        }
      }
      
      showMultiBlockModal.value = false;
      cancelSelectionWithUnblock();
      
    } catch (err) {
      console.error('Error blocking dates:', err);
      error.value = 'Failed to block selected dates';
    }
  }

  async function confirmMultiUnblock() {
    try {
      const room = rooms.value.find(r => r.id === selectedRoom.value.id);
      if (!room) return;
      
      const sortedDates = [...selectedDates.value].sort();
      
      for (const dateStr of sortedDates) {
        const date = parseDate(dateStr);
        
        const blockIndex = room.blockedDates.findIndex(block => {
          const blockStart = parseDate(block.startDate);
          const blockEnd = parseDate(block.endDate);
          return date >= blockStart && date < blockEnd;
        });
        
        if (blockIndex !== -1) {
          const block = room.blockedDates[blockIndex];
          const blockStart = parseDate(block.startDate);
          const blockEnd = parseDate(block.endDate);
          
          room.blockedDates.splice(blockIndex, 1);
          
          const dayAfter = new Date(date);
          dayAfter.setDate(dayAfter.getDate() + 1);
          
          if (blockStart < date) {
            room.blockedDates.push({
              startDate: formatDate(blockStart),
              endDate: formatDate(date)
            });
          }
          
          if (dayAfter < blockEnd) {
            room.blockedDates.push({
              startDate: formatDate(dayAfter),
              endDate: formatDate(blockEnd)
            });
          }
        }
      }
      
      showMultiBlockModal.value = false;
      cancelSelectionWithUnblock();
      
      console.log('Successfully unblocked selected dates');
      
    } catch (err) {
      console.error('Error unblocking selected dates:', err);
      error.value = 'Failed to unblock selected dates';
    }
  }

  // Offset calculations
  function getBookingOffset(booking) {
    try {
      if (!booking?.startDate || !booking?.endDate) return null;
      const start = parseDate(booking.startDate);
      const end = parseDate(booking.endDate);
      
      if (end <= firstDay.value || start > lastDay.value) return null;

      const adjustedStart = start < firstDay.value ? firstDay.value : start;
      const adjustedEnd = end > lastDay.value ? lastDay.value : end;

      const offset = daysBetween(firstDay.value, adjustedStart);
      const length = daysBetween(adjustedStart, adjustedEnd);
      return { offset, length: length > 0 ? length : 1 };
    } catch (err) {
      console.error("getBookingOffset error:", err);
      return null;
    }
  }

  function getBlockedOffset(blockedDate) {
    try {
      if (!blockedDate?.startDate || !blockedDate?.endDate) return null;
      const start = parseDate(blockedDate.startDate);
      const end = parseDate(blockedDate.endDate);
      
      if (end <= firstDay.value || start > lastDay.value) return null;

      const adjustedStart = start < firstDay.value ? firstDay.value : start;
      const adjustedEnd = end > lastDay.value ? lastDay.value : end;

      const offset = daysBetween(firstDay.value, adjustedStart);
      const length = daysBetween(adjustedStart, adjustedEnd);
      return { offset, length: length > 0 ? length : 1 };
    } catch (err) {
      console.error("getBlockedOffset error:", err);
      return null;
    }
  }

  function getDayTooltipWithUnblock(room, dateStr) {
    const date = parseDate(dateStr);
    const status = getDayStatus(room, dateStr);
    const price = getDayPrice(room, dateStr);
    let tooltip = `${room.hotel} - ${room.name}\nDate: ${dateStr}\nStatus: ${status}\nPrice: $${price}`;
    
    if (selectionMode.value && selectedRoom.value.id === room.id) {
      if (room._unblockMode) {
        if (isDateBooked(room, date)) {
          tooltip += '\nCannot unblock booked dates';
        } else if (isDateBlocked(room, date)) {
          tooltip += isDateSelected(room, dateStr) ? '\nClick to deselect for unblocking' : '\nClick to select for unblocking';
        } else {
          tooltip += '\nOnly blocked dates can be unblocked';
        }
      } else {
        if (isDateBooked(room, date)) {
          tooltip += '\nCannot select booked dates';
        } else {
          tooltip += isDateSelected(room, dateStr) ? '\nClick to deselect' : '\nClick to select for blocking';
        }
      }
    } else if (isDateBooked(room, date)) {
      const booking = room.bookings.find(b => {
        const start = parseDate(b.startDate);
        const end = parseDate(b.endDate);
        return date >= start && date < end;
      });
      tooltip += `\nGuest: ${booking?.guestName || 'N/A'}\nBooked: ${booking.startDate} to ${booking.endDate} (excl. last day)\nClick to manage (admin override)`;
    } else if (isDateBlocked(room, date)) {
      const block = room.blockedDates.find(b => {
        const start = parseDate(b.startDate);
        const end = parseDate(b.endDate);
        return date >= start && date < end;
      });
      tooltip += `\nBlocked: ${block.startDate} to ${block.endDate} (excl. last day)\nClick to edit or right-click to unblock`;
    } else {
      tooltip += `\nClick to block this date`;
    }
    
    return tooltip;
  }

  // Event handlers
  function onDayClickWithUnblock(room, dateStr) {
    if (selectionMode.value && selectedRoom.value.id === room.id) {
      if (room._unblockMode) {
        toggleDateSelectionUnblock(room, dateStr);
      } else {
        toggleDateSelection(room, dateStr);
      }
    } else if (!selectionMode.value) {
      selectedRoom.value = room;
      selectedStartDate.value = dateStr;
      
      const clickedDate = parseDate(dateStr);
      const nextDay = new Date(clickedDate);
      nextDay.setDate(nextDay.getDate() + 1);
      selectedEndDate.value = formatDate(nextDay);
      
      selectedPrice.value = getDayPrice(room, dateStr);
      selectedStatus.value = isDateBlocked(room, parseDate(dateStr)) ? 'blocked' : isDateBooked(room, parseDate(dateStr)) ? 'booked' : 'available';
      
      showPopup.value = true;
    }
  }

  function onDayHover(room, dateStr) {
    if (selectionMode.value && selectedRoom.value.id === room.id) {
      hoverDate.value = dateStr;
    }
  }

  function onBlockedDateClickWithUnblock(room, blockedDate, event) {
    if (event && event.button === 2) {
      event.preventDefault();
      unblockSingleDate(room, blockedDate);
    } else if (!selectionMode.value) {
      selectedRoom.value = room;
      selectedStartDate.value = blockedDate.startDate;
      selectedEndDate.value = blockedDate.endDate;
      selectedPrice.value = getDayPrice(room, blockedDate.startDate);
      selectedStatus.value = 'blocked';
      showPopup.value = true;
    }
  }

  function closePopup() {
    showPopup.value = false;
    selectedRoom.value = {};
    selectedStartDate.value = '';
    selectedEndDate.value = '';
    selectedPrice.value = 0;
    selectedStatus.value = '';
  }

  async function handleUpdate(updateData) {
  try {
    console.log('Update received:', updateData);

    const room = rooms.value.find(r => r.id === selectedRoom.value.id);
    if (!room) return;

    const startDate = parseDate(updateData.startDate || selectedStartDate.value);
    const endDate = parseDate(updateData.endDate || selectedEndDate.value);

    // Handle Block or Available
    if (updateData.status === 'blocked') {
      // Prevent conflict with existing bookings
      const isConflict = room.bookings.some(booking => {
        const bookingStart = parseDate(booking.startDate);
        const bookingEnd = parseDate(booking.endDate);
        return startDate < bookingEnd && endDate > bookingStart;
      });

      if (isConflict) {
        error.value = 'Cannot block dates that overlap with existing bookings';
        return;
      }

      // Remove same date range if exists to avoid duplicate
      room.blockedDates = room.blockedDates.filter(block =>
        !(parseDate(block.startDate).getTime() === startDate.getTime() &&
          parseDate(block.endDate).getTime() === endDate.getTime()
      ));

      // Push new block
      room.blockedDates.push({
        startDate: updateData.startDate || selectedStartDate.value,
        endDate: updateData.endDate || selectedEndDate.value
      });

    } else if (updateData.status === 'available') {
      // Remove block for that range
      room.blockedDates = room.blockedDates.filter(block =>
        !(parseDate(block.startDate).getTime() === startDate.getTime() &&
          parseDate(block.endDate).getTime() === endDate.getTime())
      );
    }

    // Handle Price update - this now applies to the entire date range
    if (updateData.price !== undefined) {
      // First, remove any overlapping price ranges
      room.price = room.price.filter(p => {
        const pStart = parseDate(p.startDate);
        const pEnd = parseDate(p.endDate);
        return !(pStart < endDate && pEnd > startDate);
      });

      // Then add the new price range
      room.price.push({
        startDate: updateData.startDate || selectedStartDate.value,
        endDate: updateData.endDate || selectedEndDate.value,
        amount: updateData.price
      });

      // Sort the price ranges by date
      room.price.sort((a, b) => parseDate(a.startDate) - parseDate(b.startDate));
    }

    showPopup.value = false;

  } catch (err) {
    console.error('Error updating room:', err);
    error.value = 'Failed to update room data';
  }
}


  // Single date unblock functions
  function unblockSingleDate(room, blockedDate) {
    selectedRoom.value = room;
    selectedBlockToRemove.value = blockedDate;
    showUnblockModal.value = true;
  }

  async function confirmSingleUnblock() {
    try {
      const room = rooms.value.find(r => r.id === selectedRoom.value.id);
      if (!room) return;

      room.blockedDates = room.blockedDates.filter(block =>
        !(parseDate(block.startDate).getTime() === parseDate(selectedBlockToRemove.value.startDate).getTime() &&
        parseDate(block.endDate).getTime() === parseDate(selectedBlockToRemove.value.endDate).getTime())
      );
      
      showUnblockModal.value = false;
      selectedBlockToRemove.value = {};
      selectedRoom.value = {};
      console.log("Successfully unblocked date range");
      
    } catch (err) {
      console.error('Error unblocking dates:', err);
      error.value = "Failed to unblock selected dates";
    }
  }

  // Keyboard event handlers
  function handleKeyDown(event) {
    if (event.key === 'Shift') {
      shiftPressed.value = true;
    }
    if (event.key === 'Escape' && selectionMode.value) {
      cancelSelectionWithUnblock();
    }
  }

  function handleKeyUp(event) {
    if (event.key === 'Shift') {
      shiftPressed.value = false;
    }
  }

  // Lifecycle hooks
  onMounted(() => {
    fetchRooms();
    document.addEventListener('keydown', handleKeyDown);
    document.addEventListener('keyup', handleKeyUp);
  });

  onUnmounted(() => {
    document.removeEventListener('keydown', handleKeyDown);
    document.removeEventListener('keyup', handleKeyUp);
  });

  // Watchers
  watch([selectedMonth, selectedYear], () => {
    // Optionally refetch data when month changes
    // fetchRooms();
  });

  // Return all the reactive properties and functions
  return {
    // Constants
    months,
    
    // Reactive state
    selectedMonth,
    selectedYear,
    rooms,
    loading,
    error,
    
    // Single date popup state
    showPopup,
    selectedRoom,
    selectedStartDate,
    selectedEndDate,
    selectedPrice,
    selectedStatus,
    showUnblockModal,
    selectedBlockToRemove,
    
    // Multi-date selection state
    selectionMode,
    selectedDates,
    hoverDate,
    selectionStartDate,
    selectionEndDate,
    showMultiBlockModal,
    shiftPressed,
    
    // Computed properties
    monthDays,
    firstDay,
    lastDay,
    availableDaysCount,
    bookedDaysCount,
    
    // Utility functions
    formatDate,
    parseDate,
    formatDisplayDate,
    formatWeekday,
    daysBetween,
    getNextDay,
    handleImageError,
    
    // Navigation functions
    previousMonth,
    nextMonth,
    goToToday,
    
    // Room data management
    fetchRooms,
    
    // Status checking functions
    isDateBooked,
    isDateBlocked,
    getDayPrice,
    getDayStatus,
    getDayStatusClassWithUnblock,
    
    // Multi-date selection functions
    startMultiSelection,
    startMultiUnblockSelection,
    cancelSelectionWithUnblock,
    isDateSelected,
    isDateInHoverRange,
    toggleDateSelection,
    toggleDateSelectionUnblock,
    selectDateRange,
    confirmSelectionWithUnblock,
    confirmMultiBlock,
    confirmMultiUnblock,
    
    // Offset calculations
    getBookingOffset,
    getBlockedOffset,
    getDayTooltipWithUnblock,
    
    // Event handlers
    onDayClickWithUnblock,
    onDayHover,
    onBlockedDateClickWithUnblock,
    closePopup,
    handleUpdate,
    unblockSingleDate,
    confirmSingleUnblock,
    handleKeyDown,
    handleKeyUp,
  };
}