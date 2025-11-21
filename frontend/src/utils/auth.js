/**
 * Authentication utility functions
 * Provides easy access to user data and role information
 */

/**
 * Get the current user from localStorage
 * @returns {Object|null} User object with roles and applications
 */
export const getCurrentUser = () => {
  try {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  } catch (e) {
    console.error('Failed to parse user data:', e);
    return null;
  }
};

/**
 * Get the current user's roles
 * @returns {Array<string>} Array of role names
 */
export const getUserRoles = () => {
  const user = getCurrentUser();
  return user?.roles || [];
};

/**
 * Get the current active role
 * @returns {string|null} Current role name
 */
export const getCurrentRole = () => {
  const user = getCurrentUser();
  return user?.current_role || null;
};

/**
 * Check if user has a specific role
 * @param {string} roleName - Role name to check
 * @returns {boolean}
 */
export const hasRole = (roleName) => {
  const roles = getUserRoles();
  return roles.includes(roleName);
};

/**
 * Check if user has any of the specified roles
 * @param {Array<string>} roleNames - Array of role names to check
 * @returns {boolean}
 */
export const hasAnyRole = (roleNames) => {
  const roles = getUserRoles();
  return roleNames.some(role => roles.includes(role));
};

/**
 * Check if user has all of the specified roles
 * @param {Array<string>} roleNames - Array of role names to check
 * @returns {boolean}
 */
export const hasAllRoles = (roleNames) => {
  const roles = getUserRoles();
  return roleNames.every(role => roles.includes(role));
};

/**
 * Get user's owner applications
 * @returns {Array} Array of owner applications
 */
export const getUserApplications = () => {
  const user = getCurrentUser();
  return user?.applications || [];
};

/**
 * Check if user is authenticated
 * @returns {boolean}
 */
export const isAuthenticated = () => {
  const token = localStorage.getItem('token');
  const user = getCurrentUser();
  return !!(token && user);
};

/**
 * Update user data in localStorage
 * @param {Object} userData - Updated user data
 */
export const updateUserData = (userData) => {
  localStorage.setItem('user', JSON.stringify(userData));
};

/**
 * Clear authentication data
 */
export const clearAuth = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
};
