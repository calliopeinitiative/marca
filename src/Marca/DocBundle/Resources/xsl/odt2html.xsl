<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:anim="urn:oasis:names:tc:opendocument:xmlns:animation:1.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:smil="urn:oasis:names:tc:opendocument:xmlns:smil-compatible:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:int="http://opendocumentfellowship.org/internal" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" exclude-result-prefixes="office meta config text table draw presentation   dr3d chart form script style number anim dc xlink math xforms fo   svg smil ooo ooow oooc int #default">
<xsl:variable name="lineBreak">
<xsl:text>
</xsl:text>
</xsl:variable>
<xsl:output method="html" encoding="utf8"/>
<xsl:template match="/office:document">
<html>
<head>
<xsl:apply-templates select="office:document-meta"/>
<xsl:call-template name="process-all-styles"/>
</head>
<xsl:apply-templates select="office:document-content"/>
</html>
</xsl:template>
<xsl:template match="office:document-content">
<body>
<xsl:apply-templates select="office:body/office:text"/>
<xsl:call-template name="add-footnote-bodies"/>
</body>
</xsl:template>
<xsl:key name="stylekey" match="text:p" use="@text:style-name"/>
<xsl:template match="text:p">
 <xsl:variable name="isnotlast">
  <xsl:call-template name="same-styles">
    <xsl:with-param name="thisstyle" select="@text:style-name"/>
    <xsl:with-param name="nextstyle">
     <xsl:apply-templates mode="get-text-stylename-attribute" select="following-sibling::*[position()=1]" />
    </xsl:with-param>
  </xsl:call-template>
 </xsl:variable>
 <xsl:variable name="isnotfirst">
  <xsl:call-template name="same-styles">
    <xsl:with-param name="thisstyle" select="@text:style-name"/>
    <xsl:with-param name="nextstyle">
     <xsl:apply-templates mode="get-text-stylename-attribute" select="preceding-sibling::*[position()=1]" />
    </xsl:with-param>
  </xsl:call-template>
 </xsl:variable>
 <xsl:choose>
  <xsl:when test="(@text:style-name='Paragraph_20_Programlisting')">
    <xsl:if test="$isnotfirst=0">
      <div class="{translate(@text:style-name,'.','_')}">
      <pre>
      <xsl:apply-templates mode="text-p-second-stage"  select="."/>
      </pre>
      </div>
    </xsl:if>
 </xsl:when>
 <xsl:when test="@text:style-name='Preformatted_20_Text'">
  <xsl:if test="$isnotfirst=0">
    <div class="{translate(@text:style-name,'.','_')}">
     <pre>
     <xsl:apply-templates mode="text-p-second-stage" select="."/>
     </pre>
    </div>
  </xsl:if>
 </xsl:when>
 <xsl:otherwise>
  <xsl:variable name="ispreformatted">
    <xsl:apply-templates mode="is-style-derived-from-preformatted" 
         select="//style:style[@style:name=current()/@text:style-name]"
       />
  </xsl:variable>
  <xsl:choose>
   <xsl:when test="$ispreformatted>0">
     <xsl:if test="$isnotfirst=0">      
       <pre>
       <div class="{translate(@text:style-name,'.','_')}">
          <xsl:apply-templates mode="text-p-second-stage" select="."/>
          <xsl:if test="count(node())=0"><br/></xsl:if>
       </div>
       </pre>
     </xsl:if>
   </xsl:when>
   <xsl:otherwise>
     <p class="{translate(@text:style-name,'.','_')}">
       <xsl:apply-templates/>
       <xsl:if test="count(node())=0"><br/></xsl:if>
     </p>
   </xsl:otherwise>
  </xsl:choose>
 </xsl:otherwise>
</xsl:choose>
</xsl:template>
<xsl:template mode="text-p-second-stage" match="text:p">
 <xsl:variable name="isnotlast">
  <xsl:call-template name="same-styles">
    <xsl:with-param name="thisstyle" select="@text:style-name"/>
    <xsl:with-param name="nextstyle">
     <xsl:apply-templates mode="get-text-stylename-attribute" select="following-sibling::*[position()=1]" />
    </xsl:with-param>
  </xsl:call-template>
 </xsl:variable>
 <xsl:apply-templates/> 
 <xsl:if test="number($isnotlast)=1">
   <br/>
   <xsl:apply-templates mode="text-p-second-stage" select="following-sibling::*[position()=1]"/> 
 </xsl:if>
</xsl:template>
<xsl:template mode="is-style-derived-from-preformatted" 
              match="style:style">
 <xsl:choose>
  <xsl:when test="@style:parent-style-name='Preformatted_20_Text' or @style:parent-style-name='Paragraph_20_Programlisting'" >1</xsl:when>
  <xsl:otherwise>0</xsl:otherwise>
 </xsl:choose>
</xsl:template>
<xsl:template name="same-styles"> 
 <xsl:param name="thisstyle"/> 
 <xsl:param name="nextstyle"/> 
 <xsl:choose>
  <xsl:when test="$nextstyle=$thisstyle">1</xsl:when>
  <xsl:otherwise>0</xsl:otherwise>
 </xsl:choose>
</xsl:template> 
<xsl:template match="text:span">
<span class="{translate(@text:style-name,'.','_')}" ><xsl:apply-templates/></span>
</xsl:template>
<xsl:template match="text:h">
<!-- Heading levels go only to 6 in XHTML -->
<xsl:variable name="level">
<xsl:choose>
<!-- text:outline-level is optional, default is 1 -->
<xsl:when test="not(@text:outline-level)">1</xsl:when>
<xsl:when test="@text:outline-level &gt; 6">6</xsl:when>
<xsl:otherwise>
<xsl:value-of select="@text:outline-level"/>
</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:element name="{concat('h', $level)}">
<xsl:attribute name="class">
<xsl:value-of select="translate(@text:style-name,'.','_')"/>
</xsl:attribute> <a name="{generate-id()}"/><xsl:apply-templates/>
  </xsl:element>
</xsl:template>
  <xsl:template match="text:tab">
 <xsl:text xml:space="preserve"> </xsl:text>
</xsl:template>
  <xsl:template match="text:line-break">
 <br/>
</xsl:template>
  <xsl:variable name="spaces" select="'                           '" xml:space="preserve"/>
  <xsl:template match="text:s">
<xsl:choose>
 <xsl:when test="@text:c">
  <xsl:call-template name="insert-spaces">
   <xsl:with-param name="n" select="@text:c"/>
  </xsl:call-template>
 </xsl:when>
 <xsl:otherwise>
  <xsl:text> </xsl:text>
 </xsl:otherwise>
</xsl:choose>
</xsl:template>
  <xsl:template name="insert-spaces">
<xsl:param name="n"/>
<xsl:choose>
 <xsl:when test="$n &lt;= 30">
  <xsl:value-of select="substring($spaces, 1, $n)"/>
 </xsl:when>
 <xsl:otherwise>
  <xsl:value-of select="$spaces"/>
  <xsl:call-template name="insert-spaces">
   <xsl:with-param name="n">
    <xsl:value-of select="$n - 30"/>
   </xsl:with-param>
  </xsl:call-template>
 </xsl:otherwise>
</xsl:choose>
</xsl:template>
  <xsl:template match="text:a">
   <xsl:choose>
     <xsl:when test="@xlink:href!=''">
       <a href="{@xlink:href}"><xsl:apply-templates/></a>
     </xsl:when>
     <xsl:otherwise>
       <xsl:apply-templates/>
     </xsl:otherwise>
   </xsl:choose>
</xsl:template>
  <xsl:template match="text:bookmark-start|text:bookmark">
 <a name="{@text:name}">
  <span style="font-size: 0px">
   <xsl:text> </xsl:text>
  </span>
 </a>
</xsl:template>
  <xsl:template match="text:note">
	<xsl:variable name="footnote-id" select="text:note-citation"/>
	<a href="#footnote-{$footnote-id}">
		<sup><xsl:value-of select="$footnote-id"/></sup>
	</a>
</xsl:template>
  <xsl:template match="text:note-body"/>
  <xsl:template name="add-footnote-bodies">
	<xsl:apply-templates select="//text:note" mode="add-footnote-bodies"/>
</xsl:template>
  <xsl:template match="text:note" mode="add-footnote-bodies">
	<xsl:variable name="footnote-id" select="text:note-citation"/>
	<p><a name="footnote-{$footnote-id}"><sup><xsl:value-of select="$footnote-id"/></sup>:</a></p>
	<xsl:apply-templates select="text:note-body/*"/>
</xsl:template>
  <xsl:template name="process-all-styles">
 <style type="text/css">
  /* ODF paragraphs, by default, don't have any line spacing. */
  p { margin: 0px; padding: 0px; }
  /* put a default link style in, so we can see links */
  a[href] { color: blue; text-decoration: underline; }
  <xsl:apply-templates select="office:document-styles/office:styles"/>
  <xsl:apply-templates select="office:document-content/office:automatic-styles"/>
  <xsl:call-template name="toc-styles"/>
 </style>
</xsl:template>
  <xsl:template name="toc-styles">
  <xsl:apply-templates select="//text:table-of-content" mode="toc-styles"/>
</xsl:template>
  <xsl:template match="text:table-of-content" mode="toc-styles">
  <!-- Generate styles for the ToC -->
  /* ToC styles start */
<xsl:apply-templates select="//text:h/@text:outline-level" mode="toc-styles"/>
  /* ToC styles end */
</xsl:template>
  <xsl:template match="text:h/@text:outline-level" mode="toc-styles">
  <xsl:text>.toc_outline_level_</xsl:text>
  <xsl:value-of select="."/>
  <xsl:text> { margin-left: </xsl:text>
  <xsl:value-of select=".*0.5"/>
  <xsl:text>cm; }</xsl:text>

  <xsl:value-of select="$lineBreak"/>

  <xsl:text>.toc_outline_level_</xsl:text>
  <xsl:value-of select="."/>
  <xsl:text> a { text-decoration: none } </xsl:text>

  <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="*" mode="toc-styles"/>
  <xsl:template match="office:document-styles/office:styles">
 /* Document styles start */
  <xsl:apply-templates/>
 /* Document styles end */
</xsl:template>
  <xsl:template match="office:document-content/office:automatic-styles">
 /* Automatic styles start */
  <xsl:apply-templates/>
 /* Automatic styles end */
</xsl:template>
  <xsl:template match="style:default-style">
   <xsl:choose>
    <xsl:when test="@style:family='table'">
        <xsl:text>table</xsl:text>
    </xsl:when>
    <xsl:when test="@style:family='table-cell'">
        <xsl:text>td</xsl:text>
    </xsl:when>
    <xsl:when test="@style:family='table-row'">
        <xsl:text>tr</xsl:text>
    </xsl:when>
    <xsl:when test="@style:family='paragraph'">
		<xsl:text>p</xsl:text>
    </xsl:when>
    <xsl:when test="@style:family='text'">
        <xsl:text>p</xsl:text>
    </xsl:when>
    <xsl:otherwise>
      <xsl:text>.default_</xsl:text>
      <xsl:value-of select="translate(@style:family,'.','_')"/>
    </xsl:otherwise>
   </xsl:choose>
   <xsl:text>{</xsl:text><xsl:value-of select="$lineBreak"/>
   
   <xsl:call-template name="process-styles">
     <xsl:with-param name="node" select="."/>
   </xsl:call-template>

   <xsl:text>}</xsl:text>
</xsl:template>
  <xsl:template match="style:style">
   <xsl:text>.</xsl:text>
   <xsl:value-of select="translate(@style:name,'.','_')"/>
   <xsl:text>{</xsl:text><xsl:value-of select="$lineBreak"/>

   <xsl:call-template name="process-styles">
     <xsl:with-param name="node" select="."/>
   </xsl:call-template>

   <xsl:text>}</xsl:text>
</xsl:template>
  <xsl:template name="process-styles">
  <xsl:param name="node"/>
  <xsl:if test="$node/@style:parent-style-name">
    <xsl:variable name="parentStyle" select="$node/@style:parent-style-name"/>
    <xsl:call-template name="process-styles">
      <xsl:with-param name="node" select="//style:style[@style:name=$parentStyle]"/>
    </xsl:call-template>
  </xsl:if>

  <xsl:apply-templates select="$node/style:paragraph-properties/@*" mode="styleattr"/>
  <xsl:apply-templates select="$node/style:text-properties/@*" mode="styleattr"/>
  <xsl:apply-templates select="$node/style:table-cell-properties/@*" mode="styleattr"/>
  <xsl:apply-templates select="$node/style:table-properties/@*" mode="styleattr"/>
  <xsl:apply-templates select="$node/style:table-column-properties/@*" mode="styleattr"/>
  <xsl:apply-templates select="$node/style:graphic-properties/@*" mode="styleattr"/>
</xsl:template>
  <xsl:template match="@fo:border-left|@fo:border-right|@fo:border-top|@fo:border-bottom|@fo:border|@fo:margin-left|@fo:margin-right|@fo:margin-top|@fo:margin-bottom|@fo:margin|@fo:padding-left|@fo:padding-right|@fo:padding-top|@fo:padding-bottom|@fo:padding|@fo:text-align|@fo:text-indent|@fo:font-variant|@fo:font-family|@fo:color|@fo:background-color|@fo:font-size|@svg:font-family|@fo:font-style|@fo:font-weight|@fo:line-height|@style:width" mode="styleattr">
	<!--<xsl:call-template name="pass-through" select="."/>-->
	<xsl:call-template name="pass-through" />
</xsl:template>
  <xsl:template match="@style:font-name" mode="styleattr">
 <xsl:text>font-family: '</xsl:text>
 <xsl:value-of select="."/><xsl:text>';</xsl:text>
 <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="@style:text-underline-style|@style:text-underline-type" mode="styleattr">
 <!-- CSS2 only has one type of underline.
      We can improve this when CSS3 is better supported.
   -->
 <xsl:if test="not(.='none')">
  <xsl:text>text-decoration: underline;</xsl:text>
 </xsl:if>
</xsl:template>
  <xsl:template match="@fo:text-align" mode="styleattr">
 <xsl:value-of select="local-name()"/><xsl:text>: </xsl:text>
 <xsl:choose>
  <xsl:when test=".='start'"><xsl:text>left</xsl:text></xsl:when>
  <xsl:when test=".='end'"><xsl:text>right</xsl:text></xsl:when>
  <xsl:otherwise><xsl:value-of select="."/></xsl:otherwise>
 </xsl:choose>
 <xsl:text>;</xsl:text>
 <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="@style:horizontal-pos" mode="styleattr">
 <xsl:choose>
  <!-- We can't support the others until we figure out pagination. -->
  <xsl:when test=".='left'">
    /* Left alignment */
    <xsl:text>margin-left: 0; margin-right: auto;</xsl:text>
  </xsl:when>
  <xsl:when test=".='right'">
    /* Right alignment */
    <xsl:text>margin-left: auto; margin-right: 0;</xsl:text>
  </xsl:when>
  <xsl:when test=".='center'">
    /* Centered alignment */
    <xsl:text>margin-left: auto; margin-right: auto;</xsl:text>
  </xsl:when>
 </xsl:choose>
 <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="@style:column-width" mode="styleattr">
  <xsl:text>width: </xsl:text><xsl:value-of select="."/><xsl:text>;</xsl:text>
</xsl:template>
  <xsl:template match="@*" mode="styleattr">
	<!-- don't output anything for attrs we don't understand -->
</xsl:template>
  <xsl:template name="pass-through">
 <xsl:value-of select="local-name()"/><xsl:text>: </xsl:text>
 <xsl:value-of select="."/><xsl:text>;</xsl:text>
 <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="text:list-level-style-bullet">
 <xsl:text>.</xsl:text>
 <xsl:value-of select="../@style:name"/>
 <xsl:text>_</xsl:text>
 <xsl:value-of select="@text:level"/>
 <xsl:text>{ list-style-type: </xsl:text>
 <xsl:choose>
  <xsl:when test="@text:level mod 3 = 1">disc</xsl:when>
  <xsl:when test="@text:level mod 3 = 2">circle</xsl:when>
  <xsl:when test="@text:level mod 3 = 0">square</xsl:when>
  <xsl:otherwise>decimal</xsl:otherwise>
 </xsl:choose>
 <xsl:text>;}</xsl:text>
 <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="text:list-level-style-number">
 <xsl:text>.</xsl:text>
 <xsl:value-of select="../@style:name"/>
 <xsl:text>_</xsl:text>
 <xsl:value-of select="@text:level"/>
 <xsl:text>{ list-style-type: </xsl:text>
 <xsl:choose>
  <xsl:when test="@style:num-format='1'">decimal</xsl:when>
  <xsl:when test="@style:num-format='I'">upper-roman</xsl:when>
  <xsl:when test="@style:num-format='i'">lower-roman</xsl:when>
  <xsl:when test="@style:num-format='A'">upper-alpha</xsl:when>
  <xsl:when test="@style:num-format='a'">lower-alpha</xsl:when>
  <xsl:otherwise>decimal</xsl:otherwise>
 </xsl:choose>
 <xsl:text>;}</xsl:text>
 <xsl:value-of select="$lineBreak"/>
</xsl:template>
  <xsl:template match="table:table">
 <table class="{@table:style-name}">
  <colgroup>
   <xsl:apply-templates select="table:table-column"/>
  </colgroup>
  <xsl:if test="table:table-header-rows/table:table-row">
   <thead>
   <xsl:apply-templates select="table:table-header-rows/table:table-row"/>
 </thead>
  </xsl:if>
  <tbody>
  <xsl:apply-templates select="table:table-row"/>
  </tbody>
 </table>
</xsl:template>
  <xsl:template match="table:table-column">
<col>
 <xsl:if test="@table:number-columns-repeated">
  <xsl:attribute name="span">
   <xsl:value-of select="@table:number-columns-repeated"/>
  </xsl:attribute>
 </xsl:if>
 <xsl:if test="@table:style-name">
  <xsl:attribute name="class">
   <xsl:value-of select="translate(@table:style-name,'.','_')"/>
  </xsl:attribute>
 </xsl:if>
</col>
</xsl:template>
  <xsl:template match="table:table-row">
<tr>
 <xsl:apply-templates select="table:table-cell"/>
</tr>
</xsl:template>
  <xsl:template match="table:table-cell">
 <xsl:variable name="n">
  <xsl:choose>
   <xsl:when test="@table:number-columns-repeated != 0">
 <xsl:value-of select="@table:number-columns-repeated"/>
   </xsl:when>
   <xsl:otherwise>1</xsl:otherwise>
  </xsl:choose>
 </xsl:variable>
 <xsl:call-template name="process-table-cell">
  <xsl:with-param name="n" select="$n"/>
 </xsl:call-template>
</xsl:template>
  <xsl:template name="process-table-cell">
 <xsl:param name="n"/>
 <xsl:if test="$n != 0">
  <td>
  <xsl:if test="@table:style-name">
   <xsl:attribute name="class">
 <xsl:value-of select="translate(@table:style-name,   '.','_')"/>
   </xsl:attribute>
  </xsl:if>
  <xsl:if test="@table:number-columns-spanned">
   <xsl:attribute name="colspan">
 <xsl:value-of select="@table:number-columns-spanned"/>
   </xsl:attribute>
  </xsl:if>
  <xsl:if test="@table:number-rows-spanned">
   <xsl:attribute name="rowspan">
 <xsl:value-of select="@table:number-rows-spanned"/>
   </xsl:attribute>
  </xsl:if>
  <xsl:apply-templates/>
  </td>
  <xsl:call-template name="process-table-cell">
   <xsl:with-param name="n" select="$n - 1"/>
  </xsl:call-template>
 </xsl:if>
</xsl:template>
  <xsl:key name="listTypes" match="text:list-style" use="@style:name"/>
  <xsl:template match="text:list">
 <xsl:variable name="level" select="count(ancestor::text:list)+1"/>
 
 <!-- the list class is the @text:style-name of the outermost
  <text:list> element -->
 <xsl:variable name="listClass">
  <xsl:choose>
   <xsl:when test="$level=1">
 <xsl:value-of select="@text:style-name"/>
   </xsl:when>
   <xsl:otherwise>
 <xsl:value-of select="ancestor::text:list[last()]/@text:style-name"/>
   </xsl:otherwise>
  </xsl:choose>
 </xsl:variable>
 
 <!-- Now select the <text:list-level-style-foo> element at this
  level of nesting for this list -->
 <xsl:variable name="node" select="key('listTypes',$listClass)/*[@text:level='$level']"/>

 <!-- emit appropriate list type -->
 <xsl:choose>
  <xsl:when test="local-name($node)='list-level-style-number'">
   <ol class="{concat($listClass,'_',$level)}">
 <xsl:apply-templates/>
   </ol>
  </xsl:when>
  <xsl:otherwise>
   <ul class="{concat($listClass,'_',$level)}">
 <xsl:apply-templates/>
   </ul>
  </xsl:otherwise>
 </xsl:choose>
</xsl:template>
  <xsl:template match="text:list-item">
 <li><xsl:apply-templates/></li>
</xsl:template>
  <xsl:template match="office:document-meta">
  <xsl:apply-templates/>
</xsl:template>
  <xsl:template match="office:meta">
  <xsl:comment> Metadata starts </xsl:comment>
  <xsl:apply-templates select="dc:title"/>
  <xsl:apply-templates select="dc:creator"/>
  <xsl:apply-templates select="dc:date"/>
  <xsl:apply-templates select="dc:language"/>
  <xsl:apply-templates select="dc:description"/>
  <xsl:apply-templates select="meta:keyword"/>
  <xsl:apply-templates select="meta:generator"/>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
  <xsl:comment> Metadata ends </xsl:comment>
</xsl:template>
  <xsl:template match="dc:title">
 <title><xsl:apply-templates/></title>
</xsl:template>
  <xsl:template match="dc:language">
 <meta http-equiv="content-language" content="{current()}"/>
</xsl:template>
  <xsl:template match="dc:creator">
 <meta name="author" content="{current()}"/>
 <meta name="DC.creator" content="{current()}"/>
</xsl:template>
  <xsl:template match="dc:description">
 <meta name="description" content="{current()}"/>
</xsl:template>
  <xsl:template match="dc:date">
 <meta name="revised" content="{current()}"/>
 <meta name="DC.date" content="{current()}"/>
</xsl:template>
  <xsl:template match="meta:keyword">
 <meta name="keywords" content="{current()}"/>
</xsl:template>
  <xsl:template match="meta:generator">
 <meta name="generator" content="{current()}"/>
</xsl:template>
  <xsl:param name="param_track_changes"/>
  <xsl:template match="text:tracked-changes">
  <xsl:comment> Document has track-changes on </xsl:comment>
</xsl:template>
  <xsl:template match="text:change">
 <xsl:if test="$param_track_changes">
   <xsl:variable name="id" select="@text:change-id"/>
   <xsl:variable name="change" select="//text:changed-region[@text:id=$id]"/>
   <xsl:element name="del">
     <xsl:attribute name="datetime">
       <xsl:value-of select="$change//dc:date"/>
     </xsl:attribute>
     <!--<xsl:apply-templates match="$change/text:deletion/*"/>-->
   </xsl:element>
 </xsl:if>
</xsl:template>
  <xsl:template match="office:change-info"/>
  <xsl:param name="param_baseuri"/>
  <xsl:template match="draw:frame">
  <xsl:element name="div">
    <xsl:attribute name="class">
      <xsl:value-of select="translate(@draw:style-name,'.','_')"/>
    </xsl:attribute>
    <xsl:attribute name="style">
      <!-- This border could be removed, but OOo does default
           to showing a border. -->
      border: 1px solid #888;
      <xsl:if test="@svg:width">
        width: <xsl:value-of select="@svg:width"/>;
      </xsl:if>
      <xsl:if test="@svg:height">
        height: <xsl:value-of select="@svg:height"/>;
      </xsl:if>
    </xsl:attribute>
    <xsl:apply-templates/>
  </xsl:element>
</xsl:template>
  <xsl:template match="draw:frame/draw:image">
  <xsl:element name="img">
    <xsl:attribute name="style">
      <!-- Default behaviour -->
      width: 100%;
      height: 100%;
      <xsl:if test="not(../@text:anchor-type='character')">
        display: block;
      </xsl:if>
    </xsl:attribute>

    <xsl:attribute name="alt">
      <xsl:value-of select="../svg:desc"/>
    </xsl:attribute>
    <xsl:attribute name="src">
      <xsl:value-of select="concat($param_baseuri,@xlink:href)"/>
    </xsl:attribute>
  </xsl:element>
</xsl:template>
  <xsl:template match="svg:desc"/>
  <xsl:template match="text:table-of-content">
  <!-- We don't parse the app's ToC but generate our own. -->
  <div class="toc">
    <xsl:apply-templates select="text:index-body/text:index-title"/>
    <xsl:apply-templates select="//text:h" mode="toc"/>
  </div>
</xsl:template>
  <xsl:template match="text:h" mode="toc">
  <xsl:element name="p">
    <xsl:attribute name="class">
      <xsl:text>toc_outline_level_</xsl:text>
      <xsl:choose>
        <xsl:when test="@text:outline-level">
          <xsl:value-of select="@text:outline-level"/>
        </xsl:when>
        <!-- ODF spec says that when unspecified the outline
             level should be considered to be 1. -->
        <xsl:otherwise>1</xsl:otherwise>
      </xsl:choose>
    </xsl:attribute>
    <a href="#{generate-id()}"><xsl:value-of select="."/></a>
  </xsl:element>
</xsl:template>
<!-- -->
<xsl:template mode="get-text-stylename-attribute" match="text:p">
 <xsl:value-of select="@text:style-name" />
</xsl:template>
</xsl:stylesheet>
