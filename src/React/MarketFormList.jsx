import React, {useState} from 'react';
import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketFormList, seMarketForms] = useState([{id:1,aaa:"bbb"}]);
  const removeForm = (idToRemove) => {
    seMarketForms(prevForms => prevForms.filter(marketForm => marketForm.id !== idToRemove));
  };

  return (
    <div>
      {marketFormList.map(marketForm => (
        <MarketForm
          key={marketForm.id} // Important: Provide a unique key for each item in a list
          id={marketForm.id}
          onRemove={removeForm} // Pass the removeForm function as a prop
        />
      ))}
    </div>
  );
}

export default MarketFormList;