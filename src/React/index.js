// src/index.js
import React from 'react';
import ReactDOM from 'react-dom/client'; // For React 18+
import MarketFormList from './MarketFormList.jsx';

const containerAddMarket = document.getElementById('react_add_market');
if (containerAddMarket) {
  const rootContainerAddMarket = ReactDOM.createRoot(containerAddMarket);
  rootContainerAddMarket.render(
    <React.StrictMode>
      <MarketFormList />
    </React.StrictMode>
  );
} else {
  console.warn('The HTML element with ID "react_add_market" was not found. React component cannot be mounted.');
}