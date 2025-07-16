// webpack.config.js
const path = require('path');

module.exports = {
  // 1. Entry point: where Webpack starts building your React application
  entry: './src/index.js',

  // 2. Output: where Webpack puts the compiled JavaScript bundle
  output: {
    filename: 'bundle.js', // The name of the compiled file
    path: path.resolve(__dirname, 'public/dist'), // The output directory
  },

  // 3. Mode: 'development' or 'production'
  // 'development' provides debugging tools, 'production' optimizes for size/speed
  mode: 'development', // Change to 'production' for a deployable version

  // 4. Module rules: how Webpack handles different file types
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/, // Regex to match .js and .jsx files
        exclude: /node_modules/, // Don't process files in node_modules
        use: {
          loader: 'babel-loader', // Use babel-loader for transpilation
          options: {
            presets: ['@babel/preset-env', '@babel/preset-react'], // Babel presets
          },
        },
      },
      // You can add rules for CSS, images, etc., if your React components use them
      // {
      //   test: /\.css$/,
      //   use: ['style-loader', 'css-loader'],
      // },
    ],
  },

  // 5. Resolve extensions: allows you to import files without specifying extensions
  resolve: {
    extensions: ['.js', '.jsx'],
  },

  // Optional: Dev server configuration (for faster development with live reloading)
  // If you want a full development server for your React part, you'd configure this.
  // For simply integrating into existing HTML, 'build' is often enough.
  // devServer: {
  //   static: {
  //     directory: path.join(__dirname, '.'), // Serve current directory, including index.html
  //   },
  //   compress: true,
  //   port: 9000,
  //   open: true, // Open browser automatically
  // },
};