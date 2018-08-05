namespace LocationRESTfull.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class initializationDB : DbMigration
    {
        public override void Up()
        {
            CreateTable(
                "dbo.Countries",
                c => new
                    {
                        id = c.Int(nullable: false, identity: true),
                        name = c.String(),
                        code = c.String(),
                    })
                .PrimaryKey(t => t.id);
            
            CreateTable(
                "dbo.Provinces",
                c => new
                    {
                        id = c.Int(nullable: false, identity: true),
                        name = c.String(),
                        code = c.String(),
                        country_id = c.Int(nullable: false),
                    })
                .PrimaryKey(t => t.id)
                .ForeignKey("dbo.Countries", t => t.country_id, cascadeDelete: true)
                .Index(t => t.country_id);
            
        }
        
        public override void Down()
        {
            DropForeignKey("dbo.Provinces", "country_id", "dbo.Countries");
            DropIndex("dbo.Provinces", new[] { "country_id" });
            DropTable("dbo.Provinces");
            DropTable("dbo.Countries");
        }
    }
}
