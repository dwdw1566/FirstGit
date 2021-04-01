package net.admin.action;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.product.action.Action;
import net.product.action.ActionForward;

public class adminFrontController extends HttpServlet implements javax.servlet.Servlet {
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
		 if (command.equals("/sales.ad")) {//매출관리 
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/adminFunction/Sales.jsp");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/board.ad")) {// 어드민 게시판 
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/adminFunction/board.jsp");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/member.ad")) {//회원정보 
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/adminFunction/member.jsp");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/order.ad")) {//주문확인
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/adminFunction/order.jsp");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/product.ad")) {// 상품목록 기능~
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/adminFunction/product.jsp");
				//    http://localhost/Reknow/reknowkr/main.jsp

			}else if (command.equals("/qna.ad")) {// qna게시판 관리 
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/adminFunction/QnA.jsp");
				

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