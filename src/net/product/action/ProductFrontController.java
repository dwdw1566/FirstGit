package net.product.action;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.product.action.Action;
import net.product.action.ActionForward;

public class ProductFrontController extends HttpServlet implements javax.servlet.Servlet {
	private static final long serialVersionUID = 1L;

	protected void doProcess(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {

		String sessId = null;

		String RequestURI = request.getRequestURI();
		String contextPath = request.getContextPath(); // /Reknow
		String command = RequestURI.substring(contextPath.length());
		System.out.println(contextPath);
		System.out.println(command);
		ActionForward forward = null;
		Action action = null;
		 if (command.equals("/product1.pd")) {// 한글이
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/product/remade/remade1.jsp?product_no=329&amp;cate_no=42&amp;display_group=1");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/new.pd")) {// 한글이
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/product/new/new1.jsp?product_no=489&amp;cate_no=70&amp;display_group=1");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/outer.pd")) {// 한글이
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/product/outer/outer.jsp?	detaile819.html?product_no=488&amp;cate_no=71&amp;display_group=1");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/bottom.pd")) {// 한글이
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/product/bottom/bottom1.jsp?product_no=487&cate_no=68&display_group=1");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/top.pd")) {// 한글이
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/product/top/top1.jsp?product_no=486&cate_no=43&display_group=1");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/shoes.pd")) {// 한글이
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/product/shoes/shoes1.jsp??product_no=489&cate_no=69&display_group=1");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}
		
		
		if (forward.isRedirect()) {
			response.sendRedirect(forward.getPath());
		} else {
			RequestDispatcher dispatcher = request.getRequestDispatcher(forward.getPath());
			dispatcher.forward(request, response);
		}

	}

	protected void doGet(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {
		// TODO Auto-generated method stub
		System.out.println("doGet");
		doProcess(request, response);
	}
  
	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {
		// TODO Auto-generated method stub
		System.out.println("doPost");
		doProcess(request, response);
	}

}