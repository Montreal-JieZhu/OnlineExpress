using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace LocationRESTfull.Models
{
    public class LocationContext: DbContext
    {
        public LocationContext() : base("Data Source=assignmentphone.database.windows.net;Initial Catalog=phoneContext;Persist Security Info=True;User ID=assignmentPhone;Password=ZhuJie123$")
        {

        }

        public DbSet<Country> Countries { get; set; }
        public DbSet<Province> Regions { get; set; }

    }
}