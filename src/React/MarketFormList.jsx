import React, {useState} from 'react';

import MarketForm from './MarketForm.jsx';

function MarketFormList() {
  const [marketName, setMarketName] = useState('');

  const [marketFormList, seMarketForms] = useState([{id:1,aaa:"bbb"}]);
  const removeForm = (idToRemove) => {
    seMarketForms(prevForms => prevForms.filter(marketForm => marketForm.id !== idToRemove));
  };
  const addForm = () => {
    console.log('eeeeeeeeeeeeeeeeeee');
    seMarketForms(prevForms => [...prevForms, {id:2,aaa:"ccc"}]);
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
          onRemove={removeForm} // Pass the removeForm function as a prop
        />
      ))}
    </div>
  );
}

export default MarketFormList;