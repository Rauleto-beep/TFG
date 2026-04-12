import CryptoJS from 'crypto-js';

// En un proyecto real, esta clave no debería estar "hardcoded"
// Podrías obtenerla del backend o tener una por cada sala de chat
const SECRET_KEY = 'bifrost_secret_key_ultra_secure'; 

export const cifrar = (texto) => {
    if (!texto) return "";
    return CryptoJS.AES.encrypt(texto, SECRET_KEY).toString();
};

// cryptoService.js
export const descifrar = (textoCifrado) => {
    if (!textoCifrado) return "";

    if (typeof textoCifrado !== 'string' || !textoCifrado.startsWith('U2FsdGVkX1')) {
        return textoCifrado; 
    }

    try {
        const bytes = CryptoJS.AES.decrypt(textoCifrado, SECRET_KEY);
        const originalText = bytes.toString(CryptoJS.enc.Utf8);
        
        // Si el resultado es vacío, hubo un fallo de clave (Key mismatch)
        if (!originalText) return textoCifrado; 
        
        return originalText;
    } catch (e) {
        // En lugar de lanzar un error que rompa Vue, devolvemos el texto original
        console.warn("Fallo en descifrado, devolviendo texto original");
        return textoCifrado;
    }
};