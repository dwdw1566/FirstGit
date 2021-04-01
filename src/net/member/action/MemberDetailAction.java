package net.member.action;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import net.member.db.MemberBean;
import net.member.db.MemberDAO;

public class MemberDetailAction implements Action {
	@Override
	public ActionForward execute(HttpServletRequest request, HttpServletResponse response) throws Exception {
		MemberDAO memberdao = new MemberDAO(); 
		MemberBean memberdata = new MemberBean(); 
		ActionForward forward = new ActionForward(); 
		HttpSession session = request.getSession();
		
		String id = (String) session.getAttribute("id"); 
		
		try {
			memberdata = memberdao.memberDetail(id);
			request.setAttribute("memberdetail",memberdata);
			
			System.out.println("멤버정보액션:");
			
			forward.setRedirect(false);
			forward.setPath("./reknowkr/memberLogin/info.jsp");
			return forward;
		}catch(Exception e) {
			e.printStackTrace();
		}
		return null;
	}
}
