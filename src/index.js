// src/index.js
import React from 'react';
import ReactDOM from 'react-dom/client'; // For React 18+
import MyComponent from './MyComponent.jsx';

// Find the HTML element where you want to mount your React component
const container = document.getElementById('react-app-root');

// Ensure the container element exists before trying to render
if (container) {
  // Create a React root (for React 18+)
  const root = ReactDOM.createRoot(container);

  // Render your React component into the root
  root.render(
    <React.StrictMode>
      <MyComponent />
    </React.StrictMode>
  );
} else {
  console.warn('The HTML element with ID "react-app-root" was not found. React component cannot be mounted.');
}