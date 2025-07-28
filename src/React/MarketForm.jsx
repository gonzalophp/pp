import React,{useState} from 'react';

function MarketForm({
  id,
  market,
  onRemove
}) {
  console.log("market---------------------",market);

  const [form, setForm] = useState({
    id: id,
    market: market,
    // amount: amount,
    // monthly_contribution: monthly_contribution,
    // contribution_years: contribution_years,
    // rate: rate
  });


  let inputNames = {
    amount: "market_" + market + "_amount",
    rate: "market_" + market + "_rate",
    monthly_contribution: "market_" +market + "_monthly_contribution",
    contribution_years: "market_" +market + "_contribution_years"
  };

  const clickOnRemove = (idToRemove) => {
    onRemove(idToRemove);
  };


  const onChangeHandler = (e) => {
    let r = RegExp(/^market_(\w+)_(amount|rate|monthly_contribution|contribution_years)$/);
    let matches = r.exec(e.target.name);
    let formPropertyName = '';
    if (Array.isArray(matches) && matches.length === 3 &&  typeof matches[2] === "string" ) {
      formPropertyName = matches[2];
      form[formPropertyName] = e.target.value;
      setForm(form);
    }
    console.log("matchessssssssss1 ", form);
  };

  return (
    <div>
      <div className="btn-grid">
        <button className="close-btn-grid" onClick={() => clickOnRemove(id)}>&times;</button>
      </div>
      <p>{market}</p>
      <label>
        Investment amount:
        <input name={inputNames.amount} placeholder="Investment amount" onChange={onChangeHandler}/>
      </label>

      <label>
        Investment rate:
        <input name={inputNames.rate} placeholder="Investment rate" />
      </label>

      <label>
        Investment Monthly contribution:
        <input name={inputNames.monthly_contribution} placeholder="Monthly contribution" />
      </label>

      <label>
        Years contribution:
        <input name={inputNames.contribution_years} placeholder="Years" />
      </label>
    </div>
  );
}

export default MarketForm;