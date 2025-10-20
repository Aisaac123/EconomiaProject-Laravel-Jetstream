# 🧭 Sistema de Modelos Financieros Educativo

Aplicación web interactiva desarrollada con **Laravel 12**, **Filament 4**, **Jetstream**, **Livewire 3** y **Tailwind 4**, diseñada con fines **educativos** para la comprensión y aplicación práctica de los principales **modelos financieros**.

---

## 👨‍💻 Autores

- **Isaac Jácome**
- **Brayan Caro**

---

## 🎯 Objetivo

El objetivo de esta aplicación es proporcionar una plataforma que facilite el aprendizaje y la aplicación de los distintos modelos financieros mediante simulaciones, cálculos automatizados y ejemplos prácticos.  
Permite al usuario comprender de manera didáctica los fundamentos matemáticos y financieros que sustentan cada modelo, así como observar el impacto de las variables en los resultados.

---

## ⚙️ Tecnologías principales

- **Laravel 12.x** — Framework backend robusto y escalable.
- **Filament 4.x** — Panel administrativo moderno para gestión modular.
- **Tailwind CSS 4.x** — Sistema de diseño limpio y flexible.
- **Jetstream** — Manejo de autenticación y sesiones de usuario.
- **Livewire 3.x** — Interactividad reactiva sin necesidad de JavaScript.
- **MySQL / PostgreSQL** — Base de datos relacional configurable.

---

## 🧩 Instalación

1. Clona el repositorio:

   ```bash
   git clone https://github.com/tuusuario/modelos-financieros.git
   cd modelos-financieros
   ```

2. Instala las dependencias de PHP:

   ```bash
   composer install
   ```

3. Copia el archivo de entorno y configura tu conexión:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Instala dependencias de Node y compila Tailwind:

   ```bash
   npm install
   npm run build
   ```

5. Ejecuta las migraciones y los seeders iniciales:

   ```bash
   php artisan migrate --seed
   ```

6. Inicia el servidor local:

   ```bash
   php artisan serve
   ```

---

## 🧠 Modelos financieros incluidos

La aplicación organiza los temas en categorías prácticas y teóricas, según su naturaleza de cálculo o concepto.

### 📘 Fundamentales
Incluye modelos base para la comprensión de las operaciones financieras esenciales.

- **Interés simple**
- **Interés compuesto**
- **Anualidades**

### 📗 Teóricos
Secciones orientadas a la interpretación conceptual de los fundamentos financieros.

- **Tasa de interés**
- **Capitalización**

### 📙 Aplicados / Avanzados
Modelos de mayor complejidad para simulación y análisis financiero.

- **Amortización**
- **Gradientes aritméticos y geométricos**
- **Tasa interna de retorno (TIR)**

---

## 🧮 Simulador de cálculo

Cada modelo que requiere aplicación práctica incluye su propia calculadora interactiva, la cual permite ingresar parámetros financieros y obtener resultados automáticos con soporte para diferentes periodos, tasas y tipos de gradiente.

---

## 💳 Simulador de credito

Estas simulaciones permiten analizar el comportamiento del crédito mediante tablas detalladas que muestran los valores de cada periodo: cuota, interés, amortización, saldo final y otros datos relevantes.

El sistema cuenta con cuatro modelos principales de cálculo financiero:

- 💰 Interés simple

- 📈 Interés compuesto

- 📊 Amortizaciones (francés, alemán, americano)

- 🔁 Gradientes (aritmético y geométrico, anticipado o vencido)

Cada modelo permite ingresar parámetros personalizados como capital, tasa, número de periodos o tipo de gradiente y obtener resultados automáticos con diferentes configuraciones de tiempo.

El objetivo del simulador es ofrecer una herramienta clara y educativa que facilite la comprensión de cómo evolucionan los créditos, las deudas y las inversiones bajo distintos esquemas financieros.

---

## 🧭 Propósito del proyecto

Este proyecto fue desarrollado con fines **educativos**, orientado a reforzar la comprensión práctica de los principales modelos financieros.  
Su objetivo es ofrecer una herramienta clara y funcional que facilite el aprendizaje mediante la aplicación directa de conceptos teóricos.
