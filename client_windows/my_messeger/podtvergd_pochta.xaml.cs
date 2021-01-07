using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using Windows.UI.Xaml;
using Windows.UI.Xaml.Controls;


namespace my_messeger
{
    public sealed partial class podtvergd_pochta : Page
    {

        private string M_str_sqlcon = "server=timber2602.beget.tech; user id=; password=; database=timber2602_pract";
        private MySqlConnection mysqlcon;

        ContentDialog errorDialog = new ContentDialog()
        {
            Title = "ОШИБКА!",
            CloseButtonText = "ОК",
        };

        ContentDialog truesdDialog = new ContentDialog()
        {
            Title = "Успех!",
            CloseButtonText = "ОК",
        };

        public podtvergd_pochta()
        {
            this.InitializeComponent();
        }

        private async void Do_proverk_Click(object sender, RoutedEventArgs e)
        {
            List<string> errors = new List<string>() { };
            List<string> truesd = new List<string>() { };
            mysqlcon = new MySqlConnection(M_str_sqlcon);

            MySqlCommand mysqlcom = new MySqlCommand("select * from users WHERE email = '" + Data.Value + "'", mysqlcon);
            mysqlcon.Open();
            // объект для чтения ответа сервера
            MySqlDataReader reader = mysqlcom.ExecuteReader();
            // читаем результат
            reader.Read();

            if (reader[12].ToString() == Podtcerj_mail.Text)
            {
                reader.Close();
                truesd.Add("Спасибо! Почта подтверждена!!!");
                mysqlcom = null;
                mysqlcom = new MySqlCommand("Update users set verifity_mail = '1' WHERE  email = '" + Data.Value + "'", mysqlcon);
                mysqlcom.ExecuteNonQuery();
                Frame.Navigate(typeof(messedger));
            }
            else
            {
                reader.Close();
                errors.Add("Код не верен!!!");
            } 

            if (errors.Count > 0)
            {
                errorDialog.Content = errors[0];
                ContentDialogResult result = await errorDialog.ShowAsync();
            }

            if (truesd.Count > 0)
            {
                truesdDialog.Content = truesd[0];
                ContentDialogResult result = await truesdDialog.ShowAsync();
            }
            errors.Clear();
            truesd.Clear();
            mysqlcon.Close();
        }
    }
}
