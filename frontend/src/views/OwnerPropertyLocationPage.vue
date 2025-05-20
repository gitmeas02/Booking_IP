<template>
  <div class="relative w-full h-screen">
    <!-- Google Map as background -->
    <div id="map" class="absolute inset-0 z-0"></div>

    <!-- Form panel on the left -->
    <div
      class="absolute top-15 left-10 z-10 w-[300px] bg-white bg-opacity-95 rounded-xl shadow-lg p-5 space-y-4"
    >
      <h2 class="text-xl font-bold text-neutral-800">Where is your property?</h2>

      <div>
        <label class="text-sm text-gray-700 mb-1 block">Enter your address</label>
        <input
          type="text"
          ref="addressInput"
          v-model="hotelName"
          placeholder="Start typing your address"
          class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
        />
      </div>

      <div class="text-sm text-gray-600">
        Lat: {{ location.lat ?? "—" }}<br />
        Lng: {{ location.lng ?? "—" }}
      </div>

      <button
        class="w-full bg-black text-white font-semibold py-2 rounded hover:bg-gray-800 transition"
        @click="handleContinue"
        :disabled="!hotelName || !location.lat"
      >
        Continue
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "MapLocationPicker",
  data() {
    return {
      hotelName: "",
      location: {
        lat: null,
        lng: null,
      },
      map: null,
      marker: null,
    };
  },
  mounted() {
    if (!window.google) {
      window.initMap = this.initMap;
      const script = document.createElement("script");
      script.src = `https://maps.googleapis.com/maps/api/js?key=${
        import.meta.env.VITE_GOOGLE_MAPS_API_KEY
      }&libraries=places&callback=initMap`;
      script.async = true;
      script.defer = true;
      document.head.appendChild(script);
    } else {
      this.initMap();
    }
  },
  methods: {
    initMap() {
      const center = { lat: 11.5564, lng: 104.9282 }; // Phnom Penh
      this.map = new google.maps.Map(document.getElementById("map"), {
        center,
        zoom: 13,
      });

      // Handle click on map to drop pin and get address
      this.map.addListener("click", (e) => {
        this.setLocation(e.latLng);
      });

      // Setup autocomplete
      this.$nextTick(() => {
        const input = this.$refs.addressInput;
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", this.map);

        autocomplete.addListener("place_changed", () => {
          const place = autocomplete.getPlace();
          if (!place.geometry || !place.geometry.location) return;

          const location = place.geometry.location;
          this.setLocation(location);
          this.map.panTo(location);
          this.map.setZoom(15);
          this.hotelName = place.formatted_address || place.name;
        });
      });
    },
    setLocation(latLng) {
      this.location.lat = latLng.lat();
      this.location.lng = latLng.lng();

      if (this.marker) {
        this.marker.setPosition(latLng);
      } else {
        this.marker = new google.maps.Marker({
          position: latLng,
          map: this.map,
        });
      }

      // Reverse geocoding to update address input
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: latLng }, (results, status) => {
        if (status === "OK" && results[0]) {
          this.hotelName = results[0].formatted_address;
        } else {
          console.warn("Geocoder failed: " + status);
          this.hotelName = "";
        }
      });
    },
    handleContinue() {
      alert(`Address: ${this.hotelName}\nLat: ${this.location.lat}, Lng: ${this.location.lng}`);
    },
  },
};
</script>

<style scoped>
#map {
  filter: brightness(0.95);
}
</style>
