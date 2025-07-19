// src/index.js
import React from 'react';
import ReactDOM from 'react-dom/client'; // For React 18+
import MyComponent from './MyComponent.jsx';
import AddMarketButton from './AddMarketButton.jsx';


const container = document.getElementById('react-app-root');

if (container) {
  // Create a React root (for React 18+)
  const root = ReactDOM.createRoot(container);
  root.render(
    <React.StrictMode>
      <MyComponent />
    </React.StrictMode>
  );
} else {
  console.warn('The HTML element with ID "react-app-root" was not found. React component cannot be mounted.');
}



const container2 = document.getElementById('react_add_market');
if (container2) {
  const root2 = ReactDOM.createRoot(container2);
  root2.render(
    <React.StrictMode>
      <AddMarketButton />
    </React.StrictMode>
  );
} else {
  console.warn('The HTML element with ID "react_add_market" was not found. React component cannot be mounted.');
}