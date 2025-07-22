import React, {useState, useId} from 'react';

import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketName, setMarketName] = useState('');

  const [marketFormList, setMarketForms] = useState([]);
  const removeForm = (idToRemove) => {
    setMarketForms(prevForms => prevForms.filter(marketForm => marketForm.id !== idToRemove));
  };
  const addForm = () => {
    console.log('eeeeeeeeeeeeeeeeeee     ');
    setMarketForms(prevForms => [...prevForms, {}]);
  };

  const marketNameChange = (e) => {
    setMarketName(e.target.value);
  };

  marketFormList.forEach((marketForm) => {
    console.log("ssssssssswwwwwwwwwssssssssss",marketForm.amount,marketForm.rate,marketForm.contribution_years,marketForm.monthly_contribution);
  });

  return (
    <div>
      <div id="add_market" className="add_market">
        <input name="new_market" type="text" placeholder="Market Name" value={marketName} onChange={marketNameChange}/> 
        <input type="button" value="Add Market" onClick={addForm} />
      </div>

      {marketFormList.map(marketForm => (
        <MarketForm
          key={marketForm.id} // Important: Provide a unique key for each item in a list
          id={marketForm.id}
          market={marketName}
          amount={marketForm.amount}
          monthly_contribution={marketForm.monthly_contribution}
          contribution_years={marketForm.contribution_years}
          rate={marketForm.rate}
          onRemove={removeForm} // Pass the removeForm function as a prop
        />
      ))}
    </div>
  );
}

export default MarketFormList;