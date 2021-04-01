package net.information.action;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.product.action.Action;
import net.product.action.ActionForward;

public class InfoFrontController extends HttpServlet implements javax.servlet.Servlet {
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
		 if (command.equals("/login.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/member/login.jsp");

			}else if (command.equals("/join.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/member/join.jsp");

			}else if (command.equals("/login2.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/memberLogin/login2.jsp");
			}
			else if (command.equals("/notice.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/board/product/notice.jsp");

			}else if (command.equals("/mypage.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/myshop/mypage.jsp");

			}else if (command.equals("/qna.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/board/product/QnA.jsp?board_no=6");

			}else if (command.equals("/cart.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/order/basket.jsp");

			}else if (command.equals("/order.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/board/order/list.jsp");

			}else if (command.equals("/info.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/memberLogin/info.jsp");
	
			}else if (command.equals("/wishlist.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/myshop/wishlist.jsp");

			}else if (command.equals("/write.in")) {// �ѱ���
				forward = new ActionForward();

				forward.setRedirect(false);
				forward.setPath("./reknowkr/board/product/write.jsp");
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

	protected void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {
		// TODO Auto-generated method stub
		System.out.println("doPost");
		doProcess(request, response);
	}

}