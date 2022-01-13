package main

import (
	"github.com/gin-gonic/gin"
)

func main() {
	// gin.SetMode(gin.ReleaseMode)
	serv := gin.Default()
	serv.LoadHTMLGlob("template/*") // 設定 html template loader
	serv.Static("/asset", "./asset")

	/* route */
	serv.GET("/", index)
	/* route */
	serv.Run(":8888")
}

func index(c *gin.Context) {
	data := map[string]string{
		"name": "Eason",
	}
	c.HTML(200, "header.html", map[string]string{})
	c.HTML(200, "index.html", data)
}
