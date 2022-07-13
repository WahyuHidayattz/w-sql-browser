# W-Sql Browser

W-sql Browser (Wahyu SQL Broser) is a web based tools can be used to fetch data from Mysql Server. the main feature of W-sql is light, searchable data, exportable data in CSV format, and organize data export by clients IP address.

### Main Feature :
- Searchable Data
- Exportable Data
- Multi Server in one screen
- Selectable data

### Requirements
1. PHP ^7.4
2. Jquery (Include in this repo)
3. Text Editor (Such as Notepad++, VS Code, Sublime, etc)
4. Brain (Just kidding :P)

### Setup and Configuration
1. Clone this repo to your www path (if you use Xampp put in htdocs directory)
2. Open `config.js` to configure your MYSQL connections

        {
            "export_file_name" : "exported_data.csv",
            "list_connection" : [
                {"name": "Local", "host": "localhost", "user": "wahyu", "pass": "123", "db" : "dev"},
                {"name": "Linux", "host": "localhost", "user": "wahyu", "pass": "123", "db" : "dev"},
                {"name": "Robot", "host": "localhost", "user": "wahyu", "pass": "123", "db" : "dev"}
            ]
        }

3. If you done, just save it and open in your broser
4. Happy quering

### Screenshots

![Screenshots](art/1.png?raw=true)

![Screenshots](art/2.png?raw=true)

