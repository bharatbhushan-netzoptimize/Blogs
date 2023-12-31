<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <style>
          body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 10px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="textarea"],
        .form-container input[type="file"],
        .form-container select,
        .form-container button,
        .form-container textarea {
            width: 100%;
            height: auto;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container .form-group {
            margin-bottom: 15px;
        }

        .form-container button {
            cursor: pointer;
            background-color: gray;
            font-weight: bold;
            color: aliceblue;
        }

        .navbar {
            background-color: #f2f2f2;
            height: 65px;
            padding-left: 20px;
            margin-left: 0px;
            margin-bottom: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: black;
        }

        .error-text {
            color: red;
            font-size: 12px;

        }




        .user-profile button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
     
        .user-profile button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
        }

        .filter-container {
            background-color: #f4f4f4;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-container label {
            font-weight: bold;
            padding-left: 150px;
        }

        .filter-container select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .filter-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .error-text {
            color: red;
        }

        .blog-container {
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        padding: 20px;
        margin: 0 auto;


            background-color: #fff;
        }
        .container.blog-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        
    }
    .blog-heading {
        text-align: center;
        font-size: 24px;
        background-color: wheat;
        font-weight: bolder;
    }

    .blog-subheading {
        margin-top: 15px;
        font-size: 18px;
        font-weight: bold;
    }

    .blog-images {
        text-align: center;
        margin-top: 20px;
    }

    .blog-images img {
        max-width: 100%;
        height: auto;
        margin: 10px;
        border: 1px solid #ccc; /* Add a border to each image */
        border-radius: 5px; /* Add rounded corners to each image */
    }
    .blog-content {
        margin-top: 20px;
        font-size: 16px;
        line-height: 1.5;
        color: #444; /* Change the color to your desired content color */
    }
    </style>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script defer  src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </head>
<body>
<nav class="navbar relative -top" style="background-color: #e3f2fd;">
<a href="/blogs-oops/user/dashboard.php">
            <h1>Blogs</h1>
        </a>
</nav>
