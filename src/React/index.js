// src/index.js
import React from 'react';
import ReactDOM from 'react-dom/client'; // For React 18+
import MyComponent from './MyComponent.jsx';
import AddMarketButton from './AddMarketButton.jsx';
import MarketFormList from './MarketFormList.jsx';


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



const containerAddMarket = document.getElementById('react_add_market');
if (containerAddMarket) {
  const rootContainerAddMarket = ReactDOM.createRoot(containerAddMarket);
  rootContainerAddMarket.render(
    <React.StrictMode>
      <AddMarketButton />
      <MarketFormList />
    </React.StrictMode>
  );
} else {
  console.warn('The HTML element with ID "react_add_market" was not found. React component cannot be mounted.');
}