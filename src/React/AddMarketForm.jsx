import React from 'react';

function AddMarketForm({
  handleButtonOnClick
}) {

  const onClickHandler = (e) => {    
    let newMarketInput = document.getElementsByName("new_market")[0];
    let newMarketValue = newMarketInput.value;
    handleButtonOnClick(newMarketValue);
    newMarketInput.value = "";
  };
  

  return (
    <div id="add_market" className="add_market">
      <input name="new_market" type="text" placeholder="Market Name" />
      <input type="button" value="Add Market" onClick={onClickHandler} />
    </div>
  );
}

export default AddMarketForm;