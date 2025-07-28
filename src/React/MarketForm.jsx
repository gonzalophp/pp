import React, { useState } from 'react';

const FIELD_REGEX = /^market_(\w+)_(starting_amount|annual_rate|monthly_contribution|contribution_years)$/;

function MarketForm({
  id,
  market,
  onRemove
}) {
  const [form, setForm] = useState({
    id: id,
    market: market
  });

  const inputNames = {
    market: `market_${market}_market`,
    starting_amount: `market_${market}_starting_amount`,
    annual_rate: `market_${market}_annual_rate`,
    monthly_contribution: `market_${market}_monthly_contribution`,
    contribution_years: `market_${market}_contribution_years`,
  };

  const clickOnRemove = (idToRemove) => {
    onRemove(idToRemove);
  };

  const onChangeHandler = (e) => {
    let matches = FIELD_REGEX.exec(e.target.name);
    let formPropertyName = '';
    if (Array.isArray(matches) && matches.length === 3 &&  typeof matches[2] === "string" ) {
      formPropertyName = matches[2];
      form[formPropertyName] = e.target.value;
      setForm(form);
    }
  };

  return (
    <div>
      <div className="btn-grid">
        <button type="button" className="close-btn-grid" onClick={() => clickOnRemove(id)}>&times;</button>
      </div>
      <div className="market-form">
        <label>
          Market:
          <input name={inputNames.market} value={form.market} readOnly />
        </label>
      </div>

      <label>
        Starting amount:
        <input name={inputNames.starting_amount} value={form.starting_amount} placeholder="Starting amount" onChange={onChangeHandler} />
      </label>

      <label>
        Average annual rate:
        <input name={inputNames.annual_rate} value={form.annual_rate} placeholder="Annual rate %" onChange={onChangeHandler} />
      </label>

      <label>
        Monthly contribution:
        <input name={inputNames.monthly_contribution} value={form.monthly_contribution} placeholder="Monthly contribution" onChange={onChangeHandler} />
      </label>

      <label>
        Years of monthly contribution:
        <input name={inputNames.contribution_years} value={form.contribution_years} placeholder="Years" onChange={onChangeHandler} />
      </label>
    </div>
  );
}

export default MarketForm;