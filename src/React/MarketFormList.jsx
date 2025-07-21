import React, {useState, useId} from 'react';

import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketName, setMarketName] = useState('');

  const [marketFormList, setMarketForms] = useState([]);
  const removeForm = (idToRemove) => {
    setMarketForms(prevForms => prevForms.filter(marketForm => marketForm.id !== idToRemove));
  };
  const addForm = () => {
    const newForm = {
        id: 6,
        amount: 7777,
        monthly_contribution: 88,
        contribution_years: 5,
        rate: "wwwwwwwwwwwwwwwww"
      };

    console.log('eeeeeeeeeeeeeeeeeee     ');
    setMarketForms(prevForms => [...prevForms, newForm]);
  };

  return (
    <div>
      <div id="add_market" className="add_market">
        <input name="new_market" type="text" placeholder="Market Name" value={marketName} /> 
        <input type="button" value="Add Market" onClick={addForm} />
      </div>

      {marketFormList.map(marketForm => (
        <MarketForm
          key={marketForm.id} // Important: Provide a unique key for each item in a list
          id={marketForm.id}
          amount="99"
          monthly_contribution="22"
          contribution_years="3"
          rate="XXXXXXXXXXXXXXX"
          onRemove={removeForm} // Pass the removeForm function as a prop
        />
      ))}
    </div>
  );
}

export default MarketFormList;