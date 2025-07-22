import React,{useState} from 'react';

function MarketForm({
  market,
  amount,
  monthly_contribution,
  contribution_years,
  rate
}) {
  console.log('eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee' + amount);
  console.log(amount);
  const [marketForm, set] = useState({
    market: market,
    amount: amount,
    monthly_contribution: monthly_contribution,
    contribution_years: contribution_years,
    rate: rate
  });

  return (
    <div>
        <p>{marketForm.amount} --------rrrrrrrrr------ {contribution_years}</p>
        <p>{marketForm.market}</p>
        <span>Investment amount:</span>
        <input name="amount" placeholder="Investment amount" value={marketForm.amount}/>
        <span>Investment rate:</span>
        <select name="rate">
          <option value={marketForm.rate}>{marketForm.rate}</option>
        </select>
        <span>Investment Monthly contribution:</span><input name="monthly_contribution" placeholder="Monthly contribution" value={marketForm.monthly_contribution}/>
        <span>Years contribution:</span><input name="contribution_years" placeholder="Years" value={marketForm.contribution_years} />
    </div>
  );
}

export default MarketForm;