using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace App
{
    public partial class MainPage : ContentPage
    {
        public static MainPage Instance;
        public MainPage()
        {
            InitializeComponent();
            Instance = this;
            System.Uri uri;
            System.Uri.TryCreate("http://192.168.0.242/php/photos/Piekny_Obraz.png", UriKind.Absolute, out uri);

            Task<ImageSource> result = Task<ImageSource>.Factory.StartNew(() => ImageSource.FromUri(uri));
            result.Wait();
            img.Source = (ImageSource)result.Result;
            // Define the Intent for getting images
            
        }
        public static void h1(string message)
        {

            Instance.img.Source = (ImageSource)ImageSource.FromFile(message); ;
        }
        public static  void Button_Clicked(object sender, EventArgs e)
        {

            
        }

        private  async void Button_Clicked_1(object sender, EventArgs e)
        {
            FileResult picker = await  MediaPicker.PickPhotoAsync();
            string resultFileName = await DisplayPromptAsync("Question 1", "Jak ma się nazywać?");
            string resultWho = await DisplayPromptAsync("Question 2", "Kto wysyła?");
            DisplayAlert("AA", picker.FullPath, "OK");
            send(picker.FullPath, resultFileName, resultWho);
        }

        private void send(string path, string name, string who)
        {
            Task.Run(() =>
            {
                byte[] bytes = System.IO.File.ReadAllBytes(path);
                Encoding encoding = Encoding.GetEncoding("ISO-8859-1");
                string byteMessage = encoding.GetString(bytes);
                HttpWebRequest Uploadpage = HttpWebRequest.CreateHttp("http://192.168.0.242/php/API/UploadImage.php");
                Uploadpage.Method = "POST";
                //Uploadpage.ContentLength =9999;
                Uploadpage.ContentType = "multipart/form-data; boundary=----WebKitFormBoundaryA88cFFBr8SgBd3kO";

                StreamWriter writer = new StreamWriter(Uploadpage.GetRequestStream(), Encoding.GetEncoding("ISO-8859-1"));


                writer.WriteLine("------WebKitFormBoundaryA88cFFBr8SgBd3kO");
                writer.WriteLine("Content-Disposition: form-data; name=\"fileToUpload\"; filename=\"plik.png\"");
                writer.WriteLine("Content-Type: image/png");
                writer.Write("\r\n");
                writer.WriteLine(byteMessage);
                writer.WriteLine("------WebKitFormBoundaryA88cFFBr8SgBd3kO");
                writer.WriteLine("Content-Disposition: form-data; name=\"Name\"");
                writer.Write("\r\n");
                writer.WriteLine(name);
                writer.WriteLine("------WebKitFormBoundaryA88cFFBr8SgBd3kO");
                writer.WriteLine("Content-Disposition: form-data; name=\"Who\"");
                writer.Write("\r\n");
                writer.WriteLine(who);
                writer.WriteLine("------WebKitFormBoundaryA88cFFBr8SgBd3kO");
                writer.WriteLine("Content-Disposition: form-data; name=\"submit\"");
                writer.Write("\r\n");
                writer.WriteLine("------WebKitFormBoundaryA88cFFBr8SgBd3kO--");
                writer.Flush();

                HttpWebResponse UpliadPageResponse = (HttpWebResponse)Uploadpage.GetResponse();
                StreamReader reader = new StreamReader(UpliadPageResponse.GetResponseStream());
               Console.WriteLine(reader.ReadToEnd());
            });
            

        }
    }
}
