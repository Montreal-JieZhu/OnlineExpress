using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Web;

namespace LocationRESTfull.Models
{
    public class Province
    {
        public int id { get; set; }
        public string name { get; set; }
        public string code { get; set; }

        [ForeignKey("country")]
        public int country_id { get; set; }
        public Country country { get; set; }
    }
}