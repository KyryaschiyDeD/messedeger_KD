using MySql.Data.MySqlClient;
using System;
using Windows.UI.Xaml;
using Windows.UI.Xaml.Controls;
using System.Collections.Generic;
using System.Net;
using System.Text;
using System.IO;

namespace my_messeger
{
    public sealed partial class registr : Page
    {
        private string M_str_sqlcon = "server=timber2602.beget.tech; user id=; password=; database=timber2602_pract";
        private MySqlConnection mysqlcon;

        ContentDialog errorDialog = new ContentDialog()
        {
            Title = "ОШИБКА!",
            CloseButtonText = "ОК",
        };

        public registr()
        {
           this.InitializeComponent();
        }
        
        private void Login_Click(object sender, RoutedEventArgs e)
        {
            Frame.Navigate(typeof(MainPage));
        }


        private void post_http(string url, string data)
        {
            CookieContainer cookies = new CookieContainer();

            HttpWebRequest req = (HttpWebRequest)WebRequest.Create(url);
            req.Method = "POST";
            req.Accept = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
            req.CookieContainer = cookies;
            //req.Referer = "";
            req.UserAgent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0";
            req.ContentType = "application/x-www-form-urlencoded;";
            using (var requestStream = req.GetRequestStream())
            using (var sw = new StreamWriter(requestStream))
            {
                sw.Write(data);
            }

            using (var responseStream = req.GetResponse().GetResponseStream())
            using (var sr = new StreamReader(responseStream))
            {
                var result = sr.ReadToEnd();//ответ
            }
        }


        [Obsolete]
        private async void Reg_Click(object sender, RoutedEventArgs e)
        {
            mysqlcon = new MySqlConnection(M_str_sqlcon);
            mysqlcon.Open();

            List<string> errors = new List<string>() { };

            if (Login_text.Text.Length > 0)
            {
                MySqlCommand mysql_login = new MySqlCommand("select * from users WHERE login = '" + Login_text.Text + "'", mysqlcon);
                MySqlDataReader reader_login = mysql_login.ExecuteReader();
                if (reader_login.Read())
                {
                    errors.Add("Данный логин уже зарегистрирован!");
                }
                reader_login.Close();
            }
            else
            {
                errors.Add("Введите логин!!!");
            }


            if (Email_text.Text.Length > 0)
            {
                MySqlCommand mysql_mail = new MySqlCommand("select * from users WHERE email = '" + Email_text.Text + "'", mysqlcon);
                MySqlDataReader reader_mail = mysql_mail.ExecuteReader();
                if (reader_mail.Read())
                {
                    errors.Add("Данная почта уже зарегистрирована!!!");
                }
                reader_mail.Close();
            }
            else
            {
                errors.Add("Введите почту!!!");
            }

            if (Password_text.Password.Length > 0 && Password_text_2.Password.Length > 0)
            {
                if (Password_text.Password != Password_text_2.Password)
                {
                    errors.Add("Пароли не совпадают!!!");
                }
            }
            else
            {
                errors.Add("Поле пароля пусто!!!");
            }

            if (errors.Count > 0)
            {
                errorDialog.Content = errors[0];
                ContentDialogResult result = await errorDialog.ShowAsync();
            }
            else
            {
                //Регистрируем!

                string pubIp = new System.Net.WebClient().DownloadString("https://api.ipify.org");
                DateTime date_time = DateTime.Now;
                string dt = date_time.ToString("dd-MM-yyyy в HH:mm");

                Random rnd = new Random();
                int prov = rnd.Next(1000, 1000000000);

                string passwordHash = BCrypt.Net.BCrypt.HashPassword(Password_text.Password);

                MySqlCommand save_user = new MySqlCommand();
                save_user.CommandType = System.Data.CommandType.Text;
                save_user.CommandText = "INSERT users (id, login, email, password, registration_date, user_ip, proverka, verifity_mail) " +
                    "VALUES (NULL,'" + Login_text.Text + "', '" + Email_text.Text + "', '" + passwordHash + "','" + dt + "','" + pubIp + "','" + prov + "', 0)";
                save_user.Connection = mysqlcon;
                save_user.ExecuteNonQuery();

                post_http("http://timber2602.beget.tech/scripts/otpr_mail.php", "mail="+ Email_text.Text + "&rnd="+ prov);

                Email_text.Text = "";
                Login_text.Text = "";
                Password_text.Password = "";
                Password_text_2.Password = "";
                Frame.Navigate(typeof(MainPage));
            }
            errors.Clear();
            mysqlcon.Close();
            
        }
    }
}
