package net.member.action;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.Servlet;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/MemberFrontController")
public class MemberFrontController extends HttpServlet implements Servlet {

	protected void doProcess(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		request.setCharacterEncoding("UTF-8");
		
		String RequestURI=request.getRequestURI();
		String contextPath=request.getContextPath();
		String command=RequestURI.substring(contextPath.length());
		System.out.println(contextPath);
		System.out.println(command);
		
		ActionForward forward = null;
		Action action = null;

		if(command.equals("/main.rn")) {
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./reknowkr/main.jsp");
		}
		
		else if(command.equals("/join.rn")) {
			forward = new ActionForward();

			forward.setRedirect(false);
			forward.setPath("./member/join.jsp");
		}
		
		else if(command.equals("/MemberJoinAction.mn")) {
			action = new MemberJoinAction();
			try {
				forward = action.execute(request, response);
			}catch(Exception e) {
				e.printStackTrace();
			}
		}
		else if(command.equals("/MemberLoginAction.mn")) {
			System.out.println("컨트롤러 테스트 : "+command);
			action = new MemberLoginAction();
			try {
				forward = action.execute(request, response);
			}catch(Exception e) {
				e.printStackTrace();
			}
		}
		else if(command.equals("/MemberUpdateAction.mn")) {
			System.out.println("컨트롤러 테스트 : "+command);
			action = new MemberUpdateAction();
			try {
				forward = action.execute(request, response);
			}catch(Exception e) {
				e.printStackTrace();
			}
		}
		else if(command.equals("/MemberDetailAction.mn")) {
			System.out.println("컨트롤러 테스트 : "+command);
			action = new MemberDetailAction();
			try {
				forward = action.execute(request, response);
			}catch(Exception e) {
				e.printStackTrace();
			}
		}
		if(forward.isRedirect()){
			response.sendRedirect(forward.getPath());
		}
		else {
			RequestDispatcher dispatcher = request.getRequestDispatcher(forward.getPath());
			dispatcher.forward(request, response);
		}
	}
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doProcess(request, response);
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doProcess(request, response);
	}

}
