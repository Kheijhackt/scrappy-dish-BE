<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrappy Dish BE</title>
    
    <!-- GitHub Markdown CSS -->
    <link rel="stylesheet" href="https://cloudflare.com">

    <style>
        body {
            background-color: #0d1117; /* GitHub Dark Base */
            margin: 0;
            color: #c9d1d9;
        }

        .markdown-body {
            box-sizing: border-box;
            min-width: 200px;
            max-width: 980px;
            margin: 0 auto;
            padding: 45px;
            background-color: #0d1117; 
            font-size: 16px;
        }

        /* FIX: Give code blocks a distinct "gray thingy" background and border */
        .markdown-body pre, 
        .markdown-body code {
            background-color: #161b22 !important; /* Slightly lighter than page bg */
            border: 1px solid #30363d !important; /* Subtle gray outline */
        }

        /* FIX: Inline code (not in a block) */
        .markdown-body :not(pre) > code {
            padding: 0.2em 0.4em;
            background-color: rgba(110, 118, 129, 0.4) !important;
            border: none !important;
        }

        /* FIX: Make table headers and borders visible */
        .markdown-body table th, 
        .markdown-body table td {
            border: 1px solid #30363d !important;
        }
        .markdown-body table tr {
            background-color: #0d1117 !important;
            border-top: 1px solid #30363d !important;
        }
        .markdown-body table tr:nth-child(2n) {
            background-color: #161b22 !important; /* Zebra striping */
        }

        @media (max-width: 767px) {
            .markdown-body { padding: 15px; }
        }
    </style>
</head>
<body>
    <article class="markdown-body">
        {!! $htmlContent !!}
    </article>
</body>
</html>
