package main

import (
	"github.com/gin-gonic/gin"
)

func main() {
	// gin.SetMode(gin.ReleaseMode)
	serv := gin.Default()
	serv.LoadHTMLGlob("template/*") // 設定 html template loader
	serv.GET("/", index)
	serv.Run(":8888")
}

func index(c *gin.Context) {
	data := map[string]string{
		"name": "Eason",
	}
	c.HTML(200, "index.html", data)
}
