<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 10px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="textarea"],
        .form-container select,
        .form-container button,
        .form-container textarea {
            width: 100%;
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
            height: 40px;
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
    .form-filters {
        background-color: #f4f4f4;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between; /* Align button and select box to the right */
        align-items: right; /* Vertically center content */
    }

    .form-filters form {
        display: flex;
        flex-wrap: wrap;
    }

    .form-filters label {
        margin-right: 10px;
        font-weight: bold;
    }

    .form-filters select {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .form-filters button {
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
    body {
        font-family: Arial, sans-serif;
    }

    .user-profile {
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 20px;
    }

    .user-profile h2 {
        color: #007bff;
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
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-container label {
        font-weight: bold;
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .error-text {
        color: red;
    }
    .blog-container {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        /* Style for the blog heading */
        .blog-heading {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Style for the blog subheading */
        .blog-subheading {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        /* Style for the blog content */
        .blog-content {
            font-size: 16px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="navbar">       
        <a href="/blogs-oops/user/dashboard.php">
            <h1>Blogs</h1>
        </a>
    </div>