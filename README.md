# Clock-In/Clock-Out System

## 🚀 Overview

The **Clock-In/Clock-Out System** is a web-based application designed to help users easily track their work hours. It provides features like:

- **Real-time Clock-In/Clock-Out Status**
- **Tracking Daily Work Hours**
- **Displaying Recent Records**
- **Seamless Data Integration with MySQL**

This system is perfect for employees and employers who want to ensure accuracy in managing working hours.

---

## 🛠️ Features

| **Feature**                | **Description**                                                                 |
|----------------------------|---------------------------------------------------------------------------------|
| **Today's Duration**       | Displays the total work duration for the current day.                          |
| **Yesterday's Duration**   | Shows the total work duration for the previous day.                            |
| **Clock-In/Clock-Out**     | Allows users to clock in and out with intuitive buttons.                       |
| **Recent Records**         | View the last five clock-in/clock-out records in a structured table format.    |
| **Error Prevention**       | Prevents users from clocking in twice or clocking out without clocking in.     |

---

## 💡 How It Works

1. **Clock In**: Start your work by clicking the "Clock In" button.
   - The system records the timestamp and disables the button to avoid duplicate entries.
2. **Clock Out**: End your work session by clicking the "Clock Out" button.
   - The system calculates the duration and updates the database.
3. **View Records**: Check your daily duration and recent clock-in/out history.

---

## 📋 Requirements

To run this system, you need:

- **Server**: Apache, Nginx, or any server that supports PHP.
- **Database**: MySQL or MariaDB.
- **Browser**: Any modern browser (e.g., Chrome, Firefox).

---

## 🛑 Error Handling

The system includes robust error handling mechanisms, such as:

- Preventing multiple clock-ins without clocking out.
- Displaying clear error messages for invalid actions.

---

## 🎨 User Interface Example

| **Clock In/Out Table**     | **Today's Duration Box**     | **Yesterday's Duration Box** |
|----------------------------|------------------------------|------------------------------|
| ![Table Screenshot](images/table_screenshot.png) | ![Today Box](images/today_box.png) | ![Yesterday Box](images/yesterday_box.png) |

---

## 🔧 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo-url/clock-in-out-system.git
   ```

2. Set up your database:
   ```sql
   CREATE DATABASE clockInOut;
   CREATE TABLE clock_records (
       id INT AUTO_INCREMENT PRIMARY KEY,
       clock_in_time DATETIME,
       clock_out_time DATETIME,
       duration TIME
   );
   ```

3. Update the database credentials in `clock_records.php`:
   ```php
   $mysqli = new mysqli('your-server', 'your-username', 'your-password', 'your-database');
   ```

4. Host the files on your server and access the application via the browser.

---

## 🎯 Future Enhancements

- **Admin Dashboard**: For managing multiple users and generating reports.
- **Mobile Compatibility**: Optimized views for mobile devices.
- **Notifications**: Alerts for clock-in/out reminders.

---

## 🤝 Contributing

Feel free to fork the repository and submit pull requests. Any contributions to improve the system are welcome!

---

## 📞 Support

For any issues or inquiries, please contact:

- **Email**: support@yourdomain.com
- **GitHub Issues**: [Report a Bug](https://github.com/your-repo-url/clock-in-out-system/issues)

---

## 🌟 Acknowledgments

This project was built using PHP and MySQL with inspiration to improve work productivity and time tracking. Special thanks to everyone who provided feedback and suggestions.
