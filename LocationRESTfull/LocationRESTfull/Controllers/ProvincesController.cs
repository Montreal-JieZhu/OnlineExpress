using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;
using System.Web.Http.Description;
using LocationRESTfull.Models;

namespace LocationRESTfull.Controllers
{
    public class ProvincesController : ApiController
    {
        private LocationContext db = new LocationContext();

        // GET: api/Provinces
        public IQueryable<Province> GetRegions()
        {
            return db.Regions;
        }

        // GET: api/Provinces/5
        [ResponseType(typeof(Province))]
        public IHttpActionResult GetProvince(int id)
        {
            Province province = db.Regions.Find(id);
            if (province == null)
            {
                return NotFound();
            }

            return Ok(province);
        }

        // PUT: api/Provinces/5
        [ResponseType(typeof(void))]
        public IHttpActionResult PutProvince(int id, Province province)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (id != province.id)
            {
                return BadRequest();
            }

            db.Entry(province).State = EntityState.Modified;

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!ProvinceExists(id))
                {
                    return NotFound();
                }
                else
                {
                    throw;
                }
            }

            return StatusCode(HttpStatusCode.NoContent);
        }

        // POST: api/Provinces
        [ResponseType(typeof(Province))]
        public IHttpActionResult PostProvince(Province province)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            db.Regions.Add(province);
            db.SaveChanges();

            return CreatedAtRoute("DefaultApi", new { id = province.id }, province);
        }

        // DELETE: api/Provinces/5
        [ResponseType(typeof(Province))]
        public IHttpActionResult DeleteProvince(int id)
        {
            Province province = db.Regions.Find(id);
            if (province == null)
            {
                return NotFound();
            }

            db.Regions.Remove(province);
            db.SaveChanges();

            return Ok(province);
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                db.Dispose();
            }
            base.Dispose(disposing);
        }

        private bool ProvinceExists(int id)
        {
            return db.Regions.Count(e => e.id == id) > 0;
        }
    }
}