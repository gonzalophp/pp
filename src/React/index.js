// src/index.js
import React from 'react';
import ReactDOM from 'react-dom/client'; // For React 18+
import MarketFormList from './MarketFormList.jsx';

const containerMarkets = document.getElementById('react_markets');
if (containerMarkets) {
  const rootContainerMarkets = ReactDOM.createRoot(containerMarkets);
  rootContainerMarkets.render(
    <React.StrictMode>
      <MarketFormList />
    </React.StrictMode>
  );
} else {
  console.warn('The HTML element with ID "react_markets" was not found. React component cannot be mounted.');
}