import React, {useState, useRef, useEffect} from 'react';

import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketFormList, setMarketForms] = useState([]);
  const [availableMarkets, setAvailableMarkets] = useState([]);
  const nextId = useRef(0);
  const newMarketSelectRef = useRef(null);
  const [newMarket, setNewMarket] = useState('');


  const removeForm = (idToRemove) => {
    setMarketForms(prevForms => prevForms.filter(marketForm => marketForm.id !== idToRemove));
  };

  useEffect(() => {
    // This debugging code for cookies is better placed in useEffect.
    let cookie = document.cookie;
    console.log("cookie         " + cookie);
    document.cookie.split(';').forEach(cookie => {
      const parts = cookie.split('=');
      if (parts.length === 2) {
        const name = parts[0].trim();
        const value = decodeURIComponent(parts[1].trim());
        console.log(`Name: ${name}, Value: ${value}`);
      }
    });

    const savedMarketsMeta = document.getElementById('saved_markets');
    if (savedMarketsMeta) {
      const savedMarkets = savedMarketsMeta.dataset.saved_markets;
      console.log("savedMarkets============================",savedMarkets);
      // TODO: You could parse savedMarkets and initialize the marketFormList state here.
    }

    const availableMarketsMeta = document.getElementById('available_markets');
    if (availableMarketsMeta) {
      try {
        const markets = JSON.parse(availableMarketsMeta.dataset.available_markets);
        setAvailableMarkets(markets);
      } catch (e) {
        console.error("Could not parse available markets: ", e);
      }
    } else {
      console.warn('Meta tag with id "available_markets" not found.');
    }
  }, []); // Empty dependency array ensures this runs only once on mount.

  const addForm = () => {
    if (newMarket.length === 0) {
      console.log("MARKET VACIO");
      return;
    }

    const markets = marketFormList.map(marketForm => (marketForm.market));
    if (markets.includes(newMarket)) {
      console.log("MARKET REPETIDO");
      return; 
    }

    const id = nextId.current++;
    const newForm = {
      id: id,
      market: newMarket,
      onRemove: removeForm
    };
    setMarketForms(prevForms => [...prevForms, newForm]);
  };

  return (
    <div>
      <div id="add_market" className="add_market">
        <input name="new_market" type="text" placeholder="Market Name" onChange={(e) => setNewMarket(e.target.value)} />
        <input type="button" value="Add Market" onClick={addForm()} />
      </div>

      {marketFormList.map(marketForm => (
        <MarketForm
          key={marketForm.id}
          id={marketForm.id}
          market={marketForm.market}
          amount={marketForm.amount}
          monthly_contribution={marketForm.monthly_contribution}
          contribution_years={marketForm.contribution_years}
          rate={marketForm.rate}
          onRemove={removeForm}
        />
      ))}
    </div>
  );
}

export default MarketFormList;