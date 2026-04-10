import CryptoJS from 'crypto-js';

// En un proyecto real, esta clave no debería estar "hardcoded"
// Podrías obtenerla del backend o tener una por cada sala de chat
const SECRET_KEY = 'bifrost_secret_key_ultra_secure'; 

export const cifrar = (texto) => {
    if (!texto) return "";
    return CryptoJS.AES.encrypt(texto, SECRET_KEY).toString();
};

export const descifrar = (textoCifrado) => {
    try {
        const bytes = CryptoJS.AES.decrypt(textoCifrado, SECRET_KEY);
        const originalText = bytes.toString(CryptoJS.enc.Utf8);
        return originalText || "Error al descifrar mensaje";
    } catch (e) {
        return "Mensaje no cifrado o clave incorrecta";
    }
};