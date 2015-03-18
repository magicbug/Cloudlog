<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <head>
  <title><xsl:value-of select="//queryInfo/@calledMethod"/></title>
  <style>
    body {
      background: #eee;
      font-family: Verdana, sans-serif;
      font-size: 8px;
    }
    #results table {
      border: 0px solid #000;
      border-collapse: collapse;
    }
    #results th {
      padding: 4px;
      border: 1px solid #000;
      background-color: #6AA57B;
      font-size: 11px;
    }
    #results td {
      padding: 4px;
      border: 1px solid #000;
      font-size: 11px;
    }
    #results tr.row0 {
      background-color: #A3BDF5;
    }
    #results tr.row1 {
      background-color: #9ADF9A;
    }
    img {
      border: 0px;
    }
    #footer {
      font-size: 8px;
    }
    #debug {
      border: 1px dotted #fff;
      background-color: #c00;
      color: #fff;
      font-size: 8px;
    }
    #debug td {
      padding: 4px;
      color: #fff;
      font-size: 10px;
    }
    .blank {
      background-color: transparent;
    }
    .sub {
      background-color: #cfc;
    }
    .subattr {
      background-color: #cfc;
    }
  </style>
  </head>
  <body>
  <h1>Output of '<xsl:value-of select="//queryInfo/@calledMethod"/>'</h1>
  <table id="results">
        <tr>
        <xsl:for-each select="//results/result[1]/@*">
          <th><b><xsl:value-of select="name()"/></b></th>
        </xsl:for-each>
        </tr>
        <xsl:for-each select="//results/result">
		  <tr class="row{position() mod 2}">
          <xsl:for-each select="@*">
            <td><xsl:value-of select="."/></td>
          </xsl:for-each>
          </tr>
		  <xsl:for-each select="*">
			<tr>
			  <td class="blank"></td>
			  <td class="sub"><xsl:value-of select="name()"/></td>
			  <td class="blank" colspan="20">
				<table>
				  <tr>
					<xsl:for-each select="@*">
					  <td class="subattr">
						<xsl:value-of select="name()"/> = <xsl:value-of select="."/>
					  </td>
					</xsl:for-each>
				  </tr>
				</table>
			  </td>
			</tr>
		  </xsl:for-each>
        </xsl:for-each>
  </table>
  <p/>
  <xsl:if test="//debugInfo">
  <div id="debug">
	<table>
	  <tr>
		<td><b>requestURI</b></td><td><xsl:value-of select="//debugInfo/@requestURI" /></td>
	  </tr>
	  <tr>
		<td><b>dbQuery</b></td><td><xsl:value-of select="//debugInfo/@dbQuery" /></td>
	  </tr>
	  <tr>
		<td><b>clientVersion</b></td><td><xsl:value-of select="//debugInfo/@clientVersion" /></td>
	  </tr>
	</table>
  </div>
  <p/>
  </xsl:if>
  <div id="footer">
	Retrieved from the <xsl:element name="a"><xsl:attribute name="href"><xsl:value-of select="//queryInfo/logbookURL"/>/index.php/api</xsl:attribute>HRD Web Frontend</xsl:element> API at <b><xsl:value-of select="//queryInfo/@timeStamp"/></b>. Query took <b><xsl:value-of select="//queryInfo/@executionTime"/></b> seconds.<br/>This is formatted XML using XSLT.
  </div>
  </body>
  </html>
</xsl:template>
</xsl:stylesheet>
