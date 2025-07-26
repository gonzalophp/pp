import React, {useState, useRef} from 'react';

import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketFormList, setMarketForms] = useState([]);
  const nextId = useRef(0);

  const removeForm = (idToRemove) => {
    setMarketForms(prevForms => prevForms.filter(marketForm => marketForm.id !== idToRemove));
  };
  const addForm = () => {
    let input = document.querySelector('input[name="new_market"]');
    console.log("input====================",input.value);
    const id = nextId.current++;
    const newForm = {
      id: id,
      market: input.value
    };
    setMarketForms(prevForms => [...prevForms, newForm]);
  };

  return (
    <div>
      <div id="add_market" className="add_market">
        <input name="new_market" type="text" placeholder="Market Name" />
        <input type="button" value="Add Market" onClick={addForm} />
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