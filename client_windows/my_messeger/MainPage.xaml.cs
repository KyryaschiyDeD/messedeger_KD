using MySql.Data.MySqlClient;
using Windows.UI.Xaml;
using Windows.UI.Xaml.Controls;
using CryptSharp;

// Документацию по шаблону элемента "Пустая страница" см. по адресу https://go.microsoft.com/fwlink/?LinkId=402352&clcid=0x419

namespace my_messeger
{
    /// <summary>
    /// Пустая страница, которую можно использовать саму по себе или для перехода внутри фрейма.
    /// </summary>
    public sealed partial class MainPage : Page
    {
        private string M_str_sqlcon = "server=timber2602.beget.tech; user id=timber2602_pract; password=; database=timber2602_pract";
        private MySqlConnection mysqlcon;
        public MainPage()
        {
            this.InitializeComponent();
        }


        private void Login_Click(object sender, RoutedEventArgs e)
        {
            mysqlcon = new MySqlConnection(M_str_sqlcon);
            
            MySqlCommand mysqlcom = new MySqlCommand("select * from users WHERE login = '"+ Login_text.Text +"'", mysqlcon);
            mysqlcon.Open();
            // объект для чтения ответа сервера
            MySqlDataReader reader = mysqlcom.ExecuteReader();
            // читаем результат
            reader.Read();
            bool matches = Crypter.CheckPassword(Password_text.Password, reader[3].ToString());
            if (matches)
            {
                Login_text.Text = "ЕСТЬ!!!";
                if (reader[13].ToString() == "0")
                {
                    Data.Value = reader[2].ToString();
                    Frame.Navigate(typeof(podtvergd_pochta));
                }
                else
                {
                    Frame.Navigate(typeof(messedger));
                }
            } else
            {
                Login_text.Text = "NOOOOOOOOOOOO!!!";
            }
            reader.Close();
            mysqlcon.Close();
        }

        private void Reg_Click(object sender, RoutedEventArgs e)
        {
            Frame.Navigate(typeof(registr));
        }
    }
}
