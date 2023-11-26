package test.java.com.boat.test.visualqrcode;

import java.awt.Color;
import java.io.IOException;

//import org.junit.Test;

import main.java.com.boat.visualqrcode.VisualQRCode;

/**
 * @Title: TestVisualQRCode.java
 * @Package com.boat.visualqrcode
 * @Description: TODO(用一句话描述该文件做什么)
 * @author 黄本豪
 * @date 2016年12月1日 下午3:29:53
 * @version V1.0
 */

public class TestVisualQRCode {

	private final String outPutPath = "D:\\Document And Settings2\\Administrator\\Desktop\\";

	public static void main(String[] args) {
		TestVisualQRCode qr = new TestVisualQRCode();
//		qr.testFILLCIRCLE();
//		qr.testLARGEIMG();
		qr.testPOSITIONRECTANGLE();
	}

	//@Test
	public void testPOSITIONRECTANGLE() {
		String url = "http://blog.csdn.net/weixin_41279060/article/details/78961532";
		try {
			VisualQRCode.createQRCode(url, "E:\\workspace\\IdeaProjects\\discuz\\visual-qr-code\\img\\lg-logo.jpg", outPutPath + "POSITIONRECTANGLE.png", 'M', new Color(2, 85, 43), null, null, null, true,
					VisualQRCode.POSITION_DETECTION_SHAPE_MODEL_RECTANGLE, VisualQRCode.FILL_SHAPE_MODEL_RECTANGLE);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	//@Test
	public void testFILLCIRCLE() {
		String url = "http://blog.csdn.net/weixin_41279060/article/details/78961532";
		try {
			VisualQRCode.createQRCode(url, "../../../../../../../img/lg-logo.jpg", outPutPath+"FILLCIRCLE.png", 'M', new Color(2, 85, 43), null, null, null, true,
					VisualQRCode.POSITION_DETECTION_SHAPE_MODEL_ROUND_RECTANGLE, VisualQRCode.FILL_SHAPE_MODEL_CIRCLE);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	//@Test
	public void testLARGEIMG(){
		String url = "http://blog.csdn.net/weixin_41279060/article/details/78961532";
		try {
			VisualQRCode.createQRCode(url, "../../../../../../../img/xmyrz.jpg", outPutPath+"LARGEIMG.png", 'M', new Color(170, 24, 67), 800, 420, 200, false,
					VisualQRCode.POSITION_DETECTION_SHAPE_MODEL_ROUND_RECTANGLE, VisualQRCode.FILL_SHAPE_MODEL_RECTANGLE);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
}
