import React, {useState, useRef, useEffect} from 'react';
import AddMarketForm from './AddMarketForm.jsx';
import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketFormList, setMarketForms] = useState([]);
  const [availableMarkets, setAvailableMarkets] = useState([]);
  const nextId = useRef(0);
  const newMarketSelectRef = useRef(null);

  
  
  


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
        console.log("CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC" + JSON.stringify(markets));
        setAvailableMarkets(markets);        
      } catch (e) {
        console.error("Could not parse available markets: ", e);
      }
    } else {
      console.warn('Meta tag with id "available_markets" not found.');
    }


    const newForm = {
      id: 21,
        key: 21,
        market: "Default Market",
        amount: 10000,
        monthly_contribution: 500,
        contribution_years: 10,
        rate: 5
    };    
    setMarketForms([newForm]);

  }, []);

  
  console.log("WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW" + JSON.stringify(marketFormList));

  const addForm = (newMarketName) => {
    console.log("BBBBBBBBBBBBBBBBBBBBBBBBBBBBBB " + newMarketName);
    if (!newMarketName || newMarketName.length === 0) {
      console.log("MARKET VACIO");
      return;
    }

    const markets = marketFormList.map(marketForm => (marketForm.market));
    if (markets.includes(newMarketName)) {
      console.log("MARKET REPETIDO");
      return; 
    }

    const id = nextId.current++;
    const newForm = {
      id: id,
      market: newMarketName,
      onRemove: removeForm
    };
    setMarketForms(prevForms => [...prevForms, newForm]);
  };

  
  

  console.log("AAAAAAAAAAAAAAAAAAAAA" + JSON.stringify(marketFormList));
  return (
    <div>
      <AddMarketForm handleButtonOnClick={addForm}/>
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