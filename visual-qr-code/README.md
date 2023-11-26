# visual-qr-code 
## 可以创建出设置了虚拟背景图片的二维码，设置的背景图片或logo并不是只是在二维码中心，背景图片和logo都是半透明的，让二维码更好看 

## 一、使用示例(详细情况源码中的测试用例)

#### 示例1：
##### 测试代码
```
@Test
public void testPOSITIONRECTANGLE() {
    String url = "http://blog.csdn.net/weixin_41279060/article/details/78961532";
    try {
        VisualQRCode.createQRCode(url, 
            "./img/lg-logo.jpg", 
            outPutPath + "QRCodePOSITIONRECTANGLE.png", 
            'H', 
            new Color(2, 85, 43), 
            null, 
            null, 
            null, 
            true,
            VisualQRCode.POSITION_DETECTION_SHAPE_MODEL_RECTANGLE, 
            VisualQRCode.FILL_SHAPE_MODEL_RECTANGLE);
    } catch (IOException e) {
	e.printStackTrace();
    }
}
``` 
   

##### 生成的二维码效果（方形码眼，方形小点）：
![输入图片说明](https://gitee.com/uploads/images/2018/0103/152933_ac0f74da_1705914.png "POSITIONRECTANGLE.png")
   
#### 示例2：
##### 测试代码
```
@Test
public void testFILLCIRCLE() {
    String url = "http://blog.csdn.net/weixin_41279060/article/details/78961532";
    try {
        VisualQRCode.createQRCode(url, 
            "./img/lg-logo.jpg", 
            outPutPath + "FILLCIRCLE.png",
            'H', 
            new Color(2, 85, 43), 
            null, 
            null, 
            null, 
            true,
            VisualQRCode.POSITION_DETECTION_SHAPE_MODEL_ROUND_RECTANGLE, 
            VisualQRCode.FILL_SHAPE_MODEL_CIRCLE);
    } catch (IOException e) {
        e.printStackTrace();
    }
}
```


##### 生成的二维码效果（方圆形码眼，圆形小点）：
![输入图片说明](https://gitee.com/uploads/images/2018/0103/153009_9a8e668b_1705914.png "FILLCIRCLE.png")


#### 示例3：
##### 测试代码
```
@Test
public void testLARGEIMG(){
    String url = "http://blog.csdn.net/weixin_41279060/article/details/78961532";
    try {
        VisualQRCode.createQRCode(url,
            "./img/xmyrz.jpg", 
            outPutPath+"LARGEIMG.png", 
            'H', 
            new Color(170, 24, 67), 
            800, 
            420, 
            200, 
            false,
            VisualQRCode.POSITION_DETECTION_SHAPE_MODEL_ROUND_RECTANGLE, 
            VisualQRCode.FILL_SHAPE_MODEL_RECTANGLE);
    } catch (IOException e) {
        e.printStackTrace();
    }
}
```


##### 生成的二维码效果（方圆形码眼，方形小点，设置了初始位置，没有把二维码设置成图片大小）： 
![输入图片说明](https://gitee.com/uploads/images/2018/0103/153027_3f09591e_1705914.png "LARGEIMG.png")

## 二、API使用介绍

#### 创建方法：
```
public static void createQRCode(String content, String bgPath, String outPath, char Level, Color DF, Integer startX, Integer startY, Integer QRCodeWidth, Boolean isDeformation, Integer fillPositionDetectionShapeModel, Integer fillShapeModel) throws IOException
```

#### 参数说明：
1、content 二维码内容、网址，必需   
2、bgPath 背景图片路径，必需   
3、outPath 二维码输出路径，必需  
4、Level 二维码容错等级，必需   
&nbsp;&nbsp;可选："L"、"M"、"Q"、"H"   
&nbsp;&nbsp;&nbsp;&nbsp;L水平 7%的字码可被修正   
&nbsp;&nbsp;&nbsp;&nbsp;M水平 15%的字码可被修正   
&nbsp;&nbsp;&nbsp;&nbsp;Q水平 25%的字码可被修正   
&nbsp;&nbsp;&nbsp;&nbsp;H水平 30%的字码可被修正   
5、DF 二维码颜色，必需   
startX 二维码X轴起点，非必须，默认为图片的左上角   
startY 二维码Y轴起点，非必须，默认为图片的左上角   
QRCodeWidth 二维码的宽度，非必须，默认为背景图最小边  
&nbsp;&nbsp;二维码的起始X/Y加上QRCodeWidth<=图片的宽/高   
6、isDeformation 是否把背景图变成方形，非必须，默认为false不改变图片形状   
7、fillPositionDetectionShapeModel 二维码四个大码眼的形状，目前只支持方形和圆方形  
&nbsp;&nbsp;可选：    
&nbsp;&nbsp;&nbsp;&nbsp;POSITION_DETECTION_SHAPE_MODEL_RECTANGLE   
&nbsp;&nbsp;&nbsp;&nbsp;POSITION_DETECTION_SHAPE_MODEL_ROUND_RECTANGLE   
8、fillShapeModel 二维码点的形状，目前只支持方形和圆形   
&nbsp;&nbsp;可选：    
&nbsp;&nbsp;&nbsp;&nbsp;FILL_SHAPE_MODEL_RECTANGLE   
&nbsp;&nbsp;&nbsp;&nbsp;FILL_SHAPE_MODEL_CIRCLE

## 三、使用说明
下载发行版本的jar包，引用，然后使用VisualQRCode.createQRCode创建二维码即可。

## 声明：
该二维码应用使用了http://www.swetake.com/qrcode 网站上提供的API

有什么不足或者需要可以留言或者联系本人，本人邮箱为 huangbenhao@163.com

## TIPS
maven版本会迟一点发布


                                                                                                                作者Boat