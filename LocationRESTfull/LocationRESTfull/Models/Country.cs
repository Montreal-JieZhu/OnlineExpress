using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace LocationRESTfull.Models
{
    public class Country
    {
        public int id { get; set; }
        public string name { get; set; }
        public string code { get; set; }

        public ICollection<Province> provinces { get; set; }
    }
}