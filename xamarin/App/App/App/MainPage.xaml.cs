using Newtonsoft.Json;
using System;
using System.Numerics;
using System.Data;
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
        public int page = 0;
        public static MainPage Instance;
        public MainPage()
        {
            InitializeComponent();
            Instance = this;
           
            // Define the Intent for getting images
            
        }
        public static void h1(string message)
        {

            Instance.img.Source = (ImageSource)ImageSource.FromFile(message); ;
        }
        public void display(string link)
        {
            Console.WriteLine("http://192.168.0.242" + link);
            System.Uri uri;
            System.Uri.TryCreate("http://192.168.0.242"+link, UriKind.Absolute, out uri);

            Task<ImageSource> result = Task<ImageSource>.Factory.StartNew(() => ImageSource.FromUri(uri));
            result.Wait();
            img.Source = (ImageSource)result.Result;
        }

        public static  void Button_Clicked(object sender, EventArgs e)
        {

            
        }

        private  async void Button_Clicked_1(object sender, EventArgs e)
        {
            FileResult picker = await  MediaPicker.PickPhotoAsync();
            string resultFileName = await DisplayPromptAsync("Question 1", "Jak ma się nazywać?");
            string resultWho = await DisplayPromptAsync("Question 2", "Kto wysyła?");
            send(picker.FullPath, resultFileName, resultWho);
            
        }

        private void get(string page)
        {
           
           

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
                writer.WriteLine("Content-Disposition: form-data; name=\"fileToUpload\"; filename=\"plik.jpg\"");
                writer.WriteLine("Content-Type: image/jpg");
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

        private void Button_Clicked_2(object sender, EventArgs e)
        {
            page++;
            
            HttpWebRequest Uploadpage = HttpWebRequest.CreateHttp("http://192.168.0.242/php/API/GetImages.php?Page=" + page.ToString() + "&Capacity=1");
            Uploadpage.Method = "POST";
            HttpWebResponse UpliadPageResponse = (HttpWebResponse)Uploadpage.GetResponse();
            StreamReader reader = new StreamReader(UpliadPageResponse.GetResponseStream());
            string final = reader.ReadToEnd();
            Console.WriteLine(final);
            try{
                IEnumerable<DataModel> data = JsonConvert.DeserializeObject<IEnumerable<DataModel>>(final);
                Console.WriteLine("http://192.168.0.242" + data.First().Path);
                Console.WriteLine(data.First().Path);
                display(data.First().Path);
            }
            catch (Exception ex)
            {
                page--;
                DisplayAlert("ALERT", "No more images", "Ok");
            }
            counter.Text = page.ToString();
        }

        private void Button_Clicked_3(object sender, EventArgs e)
        {
            page--;
            if (page <= 0)
                page = 0;
            HttpWebRequest Uploadpage = HttpWebRequest.CreateHttp("http://192.168.0.242/php/API/GetImages.php?Page=" + page.ToString() + "&Capacity=1");
            Uploadpage.Method = "POST";
            HttpWebResponse UpliadPageResponse = (HttpWebResponse)Uploadpage.GetResponse();
            StreamReader reader = new StreamReader(UpliadPageResponse.GetResponseStream());
            string final = reader.ReadToEnd();
            Console.WriteLine(final);
            try
            {
                IEnumerable<DataModel> data = JsonConvert.DeserializeObject<IEnumerable<DataModel>>(final);
                Console.WriteLine("http://192.168.0.242" + data.First().Path);
                Console.WriteLine(data.First().Path);
                display(data.First().Path);
            }
            catch (Exception ex)
            {
                DisplayAlert("ALERT", "No more images", "Ok");
            }
            counter.Text = page.ToString();


        }
    }
}
