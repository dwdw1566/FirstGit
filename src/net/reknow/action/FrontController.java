package net.reknow.action;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.reknow.action.Action;
import net.reknow.action.ActionForward;

public class FrontController extends HttpServlet implements javax.servlet.Servlet {
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

		if (command.equals("/main.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/main.jsp");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if(command.equals("/main2.rn")) {
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/main2.jsp");
		}
		else if (command.equals("/remade.rn")) {// �ѱ���
			forward = new ActionForward();
        
			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/remade.jsp");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/new.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/new.jsp?cate_no=70");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else 	if (command.equals("/outer.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/outer.jsp?cate_no=71");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/top.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/top.jsp?cate_no=43");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/bottom.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/bottom.jsp?cate_no=68");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/shoes.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/shoes.jsp?cate_no=69");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/vtg.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/vtg.jsp?cate_no=63");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/sale.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/product/sale.jsp?cate_no=50");
			//    http://localhost/Reknow/reknowkr/main.jsp

		}else if (command.equals("/admin.rn")) {// �ѱ���
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/adminMain.jsp");
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
