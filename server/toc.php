<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    #toc {
      position: fixed;
      left: 0;
      top: 0;
      width: 200px;
      height: 100%;
      background-color: #f0f0f0;
      padding: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
    }

    #content {
      margin-left: 220px; /* Adjust this value based on the width of your TOC */
      padding: 20px;
    }

    /* Style for TOC links */
    #toc a {
      display: block;
      margin-bottom: 10px;
      text-decoration: none;
      color: #333;
    }
  </style>
</head>
<body>
<div id="toc">
    <h2>Table of Contents</h2>
    <ul>
      <li><a onclick="loadContent('./index.php')">Page 1</a></li>
      <li><a onclick="loadContent('./map.php')">Page 2</a></li>
      <li><a onclick="loadContent('./map.php')">Page </a></li>
    </ul>
  </div>

  <div id="content">
  </div>

  <script src="../public/js/toc.js"></script> 
</body>
</html>
  </div>
</body>
</html>
