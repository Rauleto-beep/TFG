# Bifrost - Gestor de Tareas Inteligente con IA y Real-Time Chat

**Bifrost** es una plataforma integral de gestión de productividad desarrollada como Trabajo de Fin de Grado (TFG). Combina una arquitectura robusta en el backend con una interfaz reactiva y moderna, integrando asistentes inteligentes y comunicación en tiempo real.

## 🚀 Características Principales

*   **Gestión de Tareas (CRUD):** Control total sobre tareas personales y grupales con persistencia en MySQL mediante SQL nativo.
*   **Asistente Inteligente:** Integración con **FlowiseAI** y modelos de lenguaje (LLM) para la creación proactiva de tareas y asistencia al usuario.
*   **Chat en Tiempo Real:** Sistema de mensajería grupal utilizando el protocolo **Mercure** (Server-Sent Events).
*   **Calendario Visual:** Interfaz dinámica basada en **FullCalendar** para la organización temporal.
*   **Seguridad Avanzada:** Autenticación mediante **JWT (JSON Web Tokens)** y comunicaciones cifradas por **HTTPS**.
*   **Infraestructura Docker:** Entorno completamente contenedorizado para asegurar la portabilidad y facilidad de despliegue.

## 🛠️ Stack Tecnológico

### Backend
*   **Framework:** Symfony 7.x
*   **Lenguaje:** PHP 8.x
*   **Base de Datos:** MySQL (Consultas SQL nativas)
*   **Servidor Web:** Apache con soporte HTTPS
*   **Real-time:** Mercure Hub

### Frontend
*   **Framework:** Vue.js 3
*   **Estilos:** Tailwind CSS (Diseño Glassmorphism)
*   **Estado/Auth:** JWT Auth & Fetch API

### IA & Otros
*   **IA Orchestration:** FlowiseAI
*   **Túnel de red:** ngrok (para desarrollo y webhooks)
*   **Contenedores:** Docker & Docker Compose

## 📦 Instalación y Despliegue

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/bifrost.git](https://github.com/tu-usuario/bifrost.git)
   cd bifrost
