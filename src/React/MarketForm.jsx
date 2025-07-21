import React,{useState} from 'react';

function MarketForm() {
  const [marketForm, set] = useState({
    id: 1,
    market_investment_amount: '111',
    market_investment_monthly_contribution: '222',
    market_investment_contribution_years: '3',
    market_investment_rate: 'tttttttttttttt'
  });

  return (
    <div>
        <span>Investment amount:</span>
        <input name="market_investment_amount" placeholder="Investment amount" value={marketForm.market_investment_amount}/>
        <span>Investment rate:</span>
        <select name="market_investment_rate">
          <option value="aaaaa">ddddddddddddd</option>
        </select>
        <span>Investment Monthly contribution:</span><input name="market_investment_monthly_contribution" placeholder="Monthly contribution" value={marketForm.market_investment_monthly_contribution}/>
        <span>Years contribution:</span><input name="market_investment_contribution_years" placeholder="Years" value={marketForm.market_investment_contribution_years} />
    </div>
  );
}

export default MarketForm;