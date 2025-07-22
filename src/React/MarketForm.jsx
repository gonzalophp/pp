import React,{useState} from 'react';

function MarketForm({
  market,
  amount,
  monthly_contribution,
  contribution_years,
  rate,
  onChange
}) {
  const [marketForm, setMarketForm] = useState({
    market: market,
    amount: amount || '',
    monthly_contribution: monthly_contribution || '',
    contribution_years: contribution_years || '',
    rate: rate || ''
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setMarketForm(prevForm => ({
      ...prevForm,
      [name]: value
    }));
  };

  // Use useEffect to sync changes from props to state, handling cases where props might change
  React.useEffect(() => {
    setMarketForm({
      market: market,
      amount: amount || '',
      monthly_contribution: monthly_contribution || '',
      contribution_years: contribution_years || '',
      rate: rate || ''
    });
  }, [market, amount, monthly_contribution, contribution_years, rate]);

  // Trigger onChange in parent when state changes
  React.useEffect(() => {
    onChange(marketForm);
  }, [marketForm, onChange]);

  return (
    <div>
      <p>{marketForm.market}</p>

      <label>
        Investment amount:
        <input name="amount" placeholder="Investment amount" value={marketForm.amount} onChange={handleChange} />
      </label>

      <label>
        Investment rate:
        <input name="rate" placeholder="Investment rate" value={marketForm.rate} onChange={handleChange} />
      </label>

      <label>
        Investment Monthly contribution:
        <input name="monthly_contribution" placeholder="Monthly contribution" value={marketForm.monthly_contribution} onChange={handleChange} />
      </label>

      <label>
        Years contribution:
        <input name="contribution_years" placeholder="Years" value={marketForm.contribution_years} onChange={handleChange} />
      </label>
    </div>
  );
}

export default MarketForm;