import React from 'react';

function AddMarketButton() {
  return (
    <div id="add_market" class="add_market">
      <input name="new_market" type="text" placeholder="Market Name"/> 
      <input type="button" value="Add Market" onClick={() => alert("dddddddd")}/>
    </div>
  );
}

export default AddMarketButton;