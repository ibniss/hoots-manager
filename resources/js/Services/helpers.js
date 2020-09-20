/**
 * Convert a string to sentence case.
 *
 * @param {String} str
 */
export const sentenceCase = str =>
    str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
