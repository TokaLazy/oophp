// Modules
const path = require("path");

// Packages
const ExtractTextWebpackPlugin = require("extract-text-webpack-plugin");
const UglifyJSWebpackPlugin = require("uglifyjs-webpack-plugin");
const CleanWebpackPlugin = require("clean-webpack-plugin");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const StyleLintPlugin = require("stylelint-webpack-plugin");

// Variable
const development = process.env.NODE_ENV === "dev";

let cssLoader = [
  {
    loader: "css-loader",
    options: {
      minimize: !development
    }
  },
  {
    loader: "resolve-url-loader"
  }
];

if (!development) {
  cssLoader.push({
    loader: "postcss-loader",
    options: {
      sourceMap: true
    }
  });
}

// Configuration basique de webpack
let config = {
  // Fichier d'entré
  entry: ["./src/asset/js/index.js", "./src/asset/scss/style.scss"],

  // Fichier de sorti
  output: {
    path: path.resolve(__dirname, "public"),
    filename: development
      ? "asset/js/script.js"
      : "asset/js/script.[chunkhash:8].js"
  },

  // Redeploiment automatique
  watch: development,

  // Mise en place d'un sourcemap
  devtool: development ? "cheap-module-eval-source-map" : false,

  // Modules
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        use: ["babel-loader"]
      },
      {
        enforce: "pre",
        test: /\.js$/,
        use: ["eslint-loader"]
      },
      {
        test: /\.css$/,
        use: development
          ? ["style-loader", ...cssLoader]
          : ExtractTextWebpackPlugin.extract({
              fallback: "style-loader",
              use: cssLoader
            })
      },
      {
        test: /\.s(a|c)ss$/,
        use: development
          ? ["style-loader", ...cssLoader, "sass-loader"]
          : ExtractTextWebpackPlugin.extract({
              fallback: "style-loader",
              use: [...cssLoader, "sass-loader"]
            })
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        use: [
          {
            loader: "url-loader",
            options: {
              limit: 8192,
              publicPath: "../",
              outputPath: "img/"
            }
          }
        ]
      },
      {
        test: /\.(woff2?|eot|ttf|otf)$/,
        use: [
          {
            loader: "file-loader",
            options: {
              publicPath: "../",
              outputPath: "font/"
            }
          }
        ]
      }
    ]
  },

  // Plugins
  plugins: [
    new ExtractTextWebpackPlugin({
      filename: development
        ? "asset/css/style.css"
        : "asset/css/style.[contenthash:8].css"
    }),
    new HtmlWebpackPlugin({
      filename: "layout.php",
      template: "./public/views/layout.html"
    })
  ]
};

if (development) {
  config.plugins.push(new StyleLintPlugin());
} else {
  config.plugins.push(new UglifyJSWebpackPlugin());
  config.plugins.push(new CleanWebpackPlugin("./public/asset"));
}

module.exports = config;
