// src/MyComponent.jsx
import React, { useState } from 'react';

function MyComponent() {
  const [count, setCount] = useState(0);

  return (
    <div style={{
      border: '2px dashed #007bff',
      padding: '20px',
      margin: '20px 0',
      borderRadius: '8px',
      backgroundColor: '#e6f2ff',
      textAlign: 'center'
    }}>
      <h2>Hello from React!</h2>
      <p>This is a component integrated into an existing HTML page.</p>
      <p>You clicked the button {count} times.</p>
      <button
        onClick={() => setCount(count + 1)}
        style={{
          backgroundColor: '#007bff',
          color: 'white',
          padding: '10px 20px',
          border: 'none',
          borderRadius: '5px',
          cursor: 'pointer',
          fontSize: '1em'
        }}
      >
        Click me (React State)
      </button>
    </div>
  );
}

export default MyComponent;